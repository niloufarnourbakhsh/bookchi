<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;
use Modules\User\Enums\ActionType;
use Modules\User\Enums\ContactType;
use Modules\User\Services\VerificationCodeService;

class VerifyCodeRequest extends FormRequest
{
    public $contactType;
    public $action;

    public function prepareForValidation(): void
    {
        $this->contactType=ContactType::detectContactType(request()->contact);
        $this->action=ActionType::tryFrom(request()->action);
    }
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'code'=>['bail','required','integer'],
            'contact'=>['bail','required','string', ...$this->getContactValidation()],
            'action'=>['bail','required','string', new Enum(ActionType::class)]
        ];
    }

    public function getContactValidation(): array
    {
        if ($this->contactType===ContactType::Email){
            $rules = ['email:rfc,dns'];
            $rules[]=$this->action->NeedToBeUnique() ? 'unique:users,email' : 'exists:users,email';
        }else{
            $rules = ['phone:mobile,IR'];
            $rules[]=$this->action->NeedToBeUnique() ? 'unique:users,phone' :'exists:users,phone';
        }
        return  $rules;
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->isNotEmpty()){
                    return;
                }
                $verifyCode=(new VerificationCodeService())
                    ->verifyCode(
                        code: $this->validated('code'),
                        action: $this->action,
                        contact:  $this->validated('contact'),
                        validator:  $validator);
                if (! $verifyCode) $validator->errors()->add('code',' کد وارد شده معتبر نیست');
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
}
