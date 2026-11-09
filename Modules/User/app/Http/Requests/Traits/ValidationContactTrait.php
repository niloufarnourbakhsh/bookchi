<?php

namespace Modules\User\Http\Requests\Traits;

use Modules\User\Enums\ActionType;
use Modules\User\Enums\ContactType;

trait ValidationContactTrait
{


    public function getContactValidation($contactType,ActionType $actionType):array
    {
        return $contactType === ContactType::Email
            ? $this->EmailValidationRules($actionType)
            : $this->PhoneValidationRules($actionType);
    }

    public function EmailValidationRules($actionType): array
    {
        $rules = ['email:rfc,dns'];

        $rules[]= $actionType->NeedToBeUnique() ? 'unique:users,email' : 'exists:users,email';
        return $rules;
    }

    public function PhoneValidationRules($actionType) :array
    {
        $rules = ['phone:mobile,IR'];
        $rules[]=$actionType->NeedToBeUnique()
            ? 'unique:users,phone'
            : 'exists:users,phone';

        return  $rules;
    }
}
