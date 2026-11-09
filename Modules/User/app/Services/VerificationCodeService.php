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
use Modules\User\Http\Requests\Traits\BaseTrait;
use Modules\User\Http\Requests\VerifyCodeRequest;
use Random\RandomException;

class VerificationCodeService
{

    public TokenService $tokenService;
    public CacheService $cacheService;

    public function __construct()
    {
        $this->tokenService= new TokenService();
        $this->cacheService= new CacheService();
    }

    /**
     * @throws RandomException
     */
    public function CreateCode(ActionType $actionType, string $contact, ContactType $contactType, ?int $expireTime=null): int
    {

        $code=random_int(100000,999999);
        $this->cacheService->putCache(
            code:  $code,
            actionType: $actionType,
            contactType: $contactType,
            contact:  $contact ,
            expireTime: $expireTime);
        return $code;
    }

    public function checkRetryTime(ActionType $actionType, string $contact): ?int
    {
        $CacheValue=$this->cacheService->getCache($actionType, $contact);
        $remainingTime= $CacheValue
            ? Carbon::createFromTimestamp($CacheValue['expired_at'])->timestamp  - now()->timestamp
            :null;
        return $remainingTime !==0 ? $remainingTime : null;
    }

    public function verifyCode(int $code,ActionType $action,string $contact,Validator $validator) :bool
    {
        $key=$this->cacheService->getCacheKey($action, $contact);
        $cacheValue=Cache::get($key);
        if (!$cacheValue){
            $validator->errors()->add('code', ' کد وارد شده معتبر نیست');
            return  false;
        }
        $expireTime=Carbon::createFromTimestamp($cacheValue['expired_at']);
        //this part returns true or false
        return $key && now()->isBefore($expireTime) && $cacheValue['code']===$code;
    }

    public function generateToken(VerifyCodeRequest $request ): string
    {
        do{
            $token=Str::random(100);
        }while($this->tokenService->getToken($token));
        $this->tokenService->putToken($token ,$request->validated() , $request->contactType);
        return $token;
    }
    public function verifyToken($token, $action,array $contactList) :string|null

    {
        $tokenExisted = $this->tokenService->getToken($token);
        if (is_null($tokenExisted)) return null;
            $contact= $tokenExisted['contact_type']===ContactType::Email ? $contactList['email'] : $contactList['phone'];
        if (
            $tokenExisted['token']=== $token
            && $tokenExisted['contact']===$contact
             && $tokenExisted['action']===$action)
        {
            return  $tokenExisted['token'];
        }
        return  null;
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
     * @param $key
     * @return void
     */

    protected function forgetCache($key): void
    {
        Cache::forget($key);
    }

}
