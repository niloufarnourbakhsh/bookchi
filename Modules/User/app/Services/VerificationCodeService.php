<?php

namespace Modules\User\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;
use Modules\User\Emails\SendCodeEmail;
use Modules\User\Enums\ActionType;
use Modules\User\Enums\ContactType;
use Modules\User\Http\Requests\VerifyCodeRequest;
use Random\RandomException;

class VerificationCodeService
{
    const int EmailExpiryTime=3;
    const int PhoneExpiryTime=2;

    /**
     * @throws RandomException
     */
    public function CreateCode(ActionType $actionType, string $contact, ContactType $contactType, ?int $expireTime=null): int
    {
        if (is_null($expireTime)){
            $expireTime= $contactType===ContactType::Email  ? self::EmailExpiryTime : self::PhoneExpiryTime;
        }
        $expireAT=\Illuminate\Support\now()->addMinutes($expireTime);
        $code=random_int(100000,999999);
        Cache::put($this->getCacheKey($actionType, $contact),[
            'action'=>$actionType->value,
            'contact'=>$contact,
            'code'=>$code,
            'expire_at'=>$expireAT->timestamp
        ],$expireAT);
        return $code;
    }

    public function checkRetryTime(ActionType $actionType, string $contact): ?int
    {
        $CacheValue=Cache::get($this->getCacheKey($actionType, $contact));
        $remainingTime= $CacheValue
            ? Carbon::createFromTimestamp($CacheValue['expire_at'])->timestamp  - now()->timestamp
            :null;
        return $remainingTime !==0 ? $remainingTime : null;
    }

    public function verifyCode(int $code,ActionType $action,string $contact,Validator $validator) :bool
    {
        $key=$this->getCacheKey($action, $contact);
        $cacheValue=Cache::get($key);
        if (!$cacheValue){
            $validator->errors()->add('code', ' کد وارد شده معتبر نیست');
            return  false;
        }
        $expireTime=Carbon::createFromTimestamp($cacheValue['expire_at']);
        return $key && now()->isBefore($expireTime) && $cacheValue['code']===$code ? true :false;
    }

    public function generateToken(VerifyCodeRequest $request )
    {
        $tokenValue= $this->getToken($request->action, $request->validated('contact'), $request->contactType);
        if ($tokenValue){
            return $tokenValue['token'];
        }
        $token=Str::random(100);
        $expiryTime=now()->addMinutes(5);
        Cache::put('verification:after_verify:token:',[
            'token'=>$token,
            'contact'=>$request->contact,
            'action'=>$request->action ,
            'contact_type'=>$request->contactType
        ], $expiryTime);
        return $token;
    }

    public function verifyToken($token, $action,array $contactList) :string|null
    {
        $tokenExisted = $this->getToken();
        if (is_null($tokenExisted)) return null;
        $contact = $tokenExisted['contact_type'] === ContactType::Email ? $contactList['email'] : $contactList['phone'];
        if ($tokenExisted['token']=== $token &&
            $tokenExisted['contact']===$contact
        && $tokenExisted['action']===$action){
            return  $tokenExisted['token'];
        }
        return  null;
    }

    /**
     * @param ActionType $actionType
     * @param string $contact
     * @return string
     */
    public function getCacheKey(ActionType $actionType, string $contact): string
    {
        $key=hash('sha256', $actionType->value . ':' . $contact );
        return 'verification:' .$key;
    }

    public function sendCodeByEmail($code, $contact): bool
    {
        try {
            Mail::to($contact)->send(new SendCodeEmail($code));
            return true;
        }catch (\Exception $exception){
            Log::error('Error exception: '.$exception);
            return false;
        }
    }

    public function sendCodeBySMS($code, $contact) :bool
    {
        try {
            Log::info("کد یکبار مصرف {$code} برای شماره ی  {$contact}");
            return true;
        }catch (\Exception $exception){
            Log::error('send code Exception: '. $exception);
            return  false;
        }
    }

    /**
     * @return mixed
     */
    public function getToken(): mixed
    {
        return Cache::get('verification:after_verify:token:');
    }
    protected function forgetCache($key): void
    {
        Cache::forget($key);
    }

}
