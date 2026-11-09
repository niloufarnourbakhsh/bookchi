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

class SendVerificationRequest extends FormRequest
{
    use BaseTrait , ValidationContactTrait;
    public $contactType;
    public $actionType;

    public function prepareForValidation(): void
    {
        $this->contactType = ContactType::detectContactType($this->contact);
        $this->actionType = ActionType::tryFrom($this->action);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'action' => ['bail', 'required', 'string', new Enum(ActionType::class)],
            'contact' => ['bail', 'required', ...$this->getContactValidation($this->contactType, $this->actionType)]
        ];
    }


    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }
                $time = (new VerificationCodeService())->checkRetryTime(actionType: $this->actionType, contact: $this->validated('contact'));
                if (!is_null($time)) {
                    $validator->errors()->add('contact', "{$time} ثانیه دیگر صبر کنید. ");
                }
            }
        ];
    }


}
