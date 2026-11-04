<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;
use Modules\User\Enums\ActionType;
use Modules\User\Enums\ContactType;
use Modules\User\Services\VerificationCodeService;

class SendVerificationRequest extends FormRequest
{
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
            'contact' => ['bail', 'required', ...$this->generateContactRule()]
        ];
    }

    public function generateContactRule(): array
    {
        if ($this->contactType === ContactType::Email) {
            $rules = ['email:rfc,dns'];
            if ($this->actionType->NeedToBeUnique()) {
                $rules[] = 'unique:users,email';
            }
            $rules[] = 'exists:users,email';
        } else {
            $rules = ['phone:mobile,IR'];
            if ($this->actionType->NeedToBeUnique()) {
                return ['unique:users,phone', 'phone:mobile,IR'];
            }
            $rules[] = 'exists:users,phone';
        }
        return $rules;
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

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
