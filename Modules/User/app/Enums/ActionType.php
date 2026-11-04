<?php

namespace Modules\User\Enums;

enum ActionType: string{
    case Login='login';
    case Register='register';

    public function NeedToBeUnique(): bool
    {
        return in_array($this, [self::Register]);
    }
    public function NeedToBeExists(): bool
    {
        return in_array($this,[self::Login]);
    }
}
