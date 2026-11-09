<?php

namespace Modules\User\Http\Requests\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use Modules\User\Enums\ActionType;
use Modules\User\Models\User;

trait UserAuthenticationTrait
{
    /**
     * @param $contact
     * @param Validator $validator
     * @return User|null
     */
    public function findUserByContactInfo($contact , Validator $validator): ?User
    {
        $user = User::query()
            ->where('email', $contact)
            ->orWhere('phone', $contact)
            ->first();
        if (! $user) {
            $validator->errors()->add(key: 'contact', message: "کاربری یافت نشد");
        }
        return $user;
    }

    protected function loginWithToken(array $validatedData, Validator $validator):void
    {
        $contactList=[
            'email'=>$validatedData['contact'],
            'phone'=>$validatedData['contact']
        ];
        $verifiedToken= $this->verifyToken(
            token: $validatedData['token'] ,
            action: ActionType::Login,
            contactList: $contactList
        );
        if (!$verifiedToken){
            $validator->errors()->add('token','توکن ارسال شده معتبر نیست');

        }
        $user=$this->findUserByContactInfo($validatedData['contact'], $validator);
        if (!$user) return;
        Auth::onceUsingId($user->id);
    }

    protected function loginWithPassword($validatedData,Validator $validator): void
    {

        if (is_null($validatedData['password'])){
            $validator->errors()->add('password','لطفا رمز عبور خود را وارد کنید.');
        }
        $user = $this->findUserByContactInfo($validatedData['contact'] ,$validator);
        if (!$user) return;
        if (!$user->password == bcrypt($validatedData['password'])){
            $validator->errors()->add('password','رمز عبور نادرست است');
        }
        Auth::onceUsingId($user->id);
    }
}
