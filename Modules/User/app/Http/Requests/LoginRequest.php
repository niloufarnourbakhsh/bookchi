<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use Modules\User\Enums\ActionType;
use Modules\User\Enums\ContactType;
use Modules\User\Http\Requests\Traits\BaseTrait;
use Modules\User\Http\Requests\Traits\TokenVerificationTrait;
use Modules\User\Http\Requests\Traits\UserAuthenticationTrait;
use Modules\User\Http\Requests\Traits\ValidationContactTrait;
use Modules\User\Models\User;
use Modules\User\Services\VerificationCodeService;

class LoginRequest extends FormRequest
{
    use BaseTrait , TokenVerificationTrait , UserAuthenticationTrait, ValidationContactTrait;
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
            'contact'=>['bail','required', ...$this->getContactValidation($this->contactType, ActionType::Login)],
            'password'=>['bail', 'string','min:8'],
            'token'=>['bail','string','max:256']
        ];
    }

    public function after():array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->isNotEmpty()) return;

                if ($this->login_type === 'token') {

                    $this->loginWithToken(validatedData: $this->validated(), validator: $validator);

                } else {

                    $this->loginWithPassword(validatedData: $this->validated(), validator: $validator);

                }
            }
        ];

    }




}
