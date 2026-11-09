<?php

namespace Modules\User\Http\Requests\Traits;

use Modules\User\Enums\ActionType;
use Modules\User\Services\VerificationCodeService;

trait TokenVerificationTrait
{
    public function verifyToken( string $token ,ActionType $action ,array $contactList): ?string
    {
       $TokenData=(new  VerificationCodeService())
            ->verifyToken( token: $token,
                action: $action->value,
                contactList:  $contactList);
       if (!$TokenData) return null;
       return $TokenData;
    }

}
