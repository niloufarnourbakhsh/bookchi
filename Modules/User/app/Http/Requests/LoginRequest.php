<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use Modules\User\Enums\ActionType;
use Modules\User\Enums\ContactType;
use Modules\User\Models\User;
use Modules\User\Services\VerificationCodeService;

class LoginRequest extends FormRequest
{
    public $contactType;

    public function prepareForValidation(): void
    {
        $this->contactType=ContactType::detectContactType($this->contact);
    }
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'login_type'=>['bail', 'string', 'in:password,token'],
            'contact'=>['bail','required', ...$this->getContactValidation()],
            'password'=>['bail', 'string','min:8'],
            'token'=>['bail','string','max:256']
        ];
    }

    public function getContactValidation():array
    {
        if ($this->contactType===ContactType::Email){
            return ['email:rfc,dns','exists:users,email'];
        }else{
            return ['phone:mobile,IR', 'exists:users,phone'];
        }
    }

    public function after():array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->isNotEmpty())return;

                if ($this->login_type==='token'){
                   $login= $this->loginWithToken(validatedData:  $this->validated(),validator:  $validator);
                }else{
                   $login= $this->loginWithPassword(validatedData: $this->validated(),validator:  $validator);
                }
                if (!$login){
                    $validator->errors()->add('contact','عملیات ورود با مشکل برخورد');
                }

            }
        ];

    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    private function loginWithToken(array $validatedData, Validator $validator): bool
    {
        $contactList=[
            'email'=>$validatedData['contact'],
            'phone'=>$validatedData['contact']
        ];
        $verifiedToken=(new VerificationCodeService())->verifyToken(
           token: $validatedData['token'],
            action: ActionType::Login,
            contactList: $contactList
        );
        if (!$verifiedToken){
            $validator->errors()->add('token','توکن ارسال شده معتبر نیست');
            return false;
        }
        $user=User::query()
            ->where('phone', $validatedData['contact'])
            ->orWhere('email', $validatedData['contact'])
            ->first();
        if (!$user){
            $validator->errors()->add(key: 'contact', message: "کاربری یافت نشد");
            return false;
        }
        Auth::onceUsingId($user->id);
        return true;
    }

    private function loginWithPassword($validatedData, $validator):bool
    {
        if (is_null($validatedData['password'])){
            $validator->errors()->add('password','لطفا رمز عبور خود را وارد کنید.');
        }
        $user=User::query()
            ->where('email', $validatedData['contact'])
            ->orWhere('phone',$validatedData['contact'])
            ->first();
        if (!$user){
            $validator->errors()->add('contact','کاربری با این اطلاعات موجود نیست');
            return false;
        }
        if (!$user->password == bcrypt($validatedData['password'])){
            $validator->errors()->add('password','رمز عبور نادرست است');
            return false;
        }
        Auth::onceUsingId($user->id);
        return  true;
    }
}
