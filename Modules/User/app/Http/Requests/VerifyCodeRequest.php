<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;
use Modules\User\Enums\ActionType;
use Modules\User\Enums\ContactType;
use Modules\User\Http\Requests\Traits\BaseTrait;
use Modules\User\Http\Requests\Traits\ValidationContactTrait;
use Modules\User\Services\VerificationCodeService;

class VerifyCodeRequest extends FormRequest
{
    use BaseTrait , ValidationContactTrait;
    public $contactType;
    public $actionType;

    public function prepareForValidation(): void
    {
        $this->contactType=ContactType::detectContactType(request()->contact);
        $this->actionType=ActionType::tryFrom(request()->action);
    }
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'code'=>['bail','required','integer'],
            'contact'=>['bail','required','string', ...$this->getContactValidation($this->contactType, $this->actionType)],
            'action'=>['bail','required','string', new Enum(ActionType::class)]
        ];
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
                        action: $this->actionType,
                        contact:  $this->validated('contact'),
                        validator:  $validator);
                if (! $verifyCode) $validator->errors()->add('code',' کد وارد شده معتبر نیست');
            }
        ];
    }

}
