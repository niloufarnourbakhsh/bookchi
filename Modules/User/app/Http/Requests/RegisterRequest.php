<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Modules\User\Enums\ActionType;
use Modules\User\Http\Requests\Traits\BaseTrait;
use Modules\User\Http\Requests\Traits\TokenVerificationTrait;
use Modules\User\Services\VerificationCodeService;

class RegisterRequest extends FormRequest
{
    use BaseTrait , TokenVerificationTrait;
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name'=>['bail','required','string','min:3','max:30'],
            'email'=>['bail','required','email:rfc,dns','unique:users,email'],
            'phone'=>['bail','required','phone:mobile,IR','unique:users,phone'],
            'password'=>['bail','required','min:6','confirmed:password'],
            'token'=>['bail','required','string']
        ];
    }

    public function after():array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->isNotEmpty()){
                    return;
                }
                $contactList=[
                    'email'=>$this->email,
                    'phone'=>$this->phone
                ];

                $verifyToken= $this->verifyToken(token: $this->token , action: ActionType::Register, contactList: $contactList);
                if (!$verifyToken){
                    $validator->errors()->add('token', 'توکن منقضی شده');
                    return;
                }
            }
        ];
    }


}
