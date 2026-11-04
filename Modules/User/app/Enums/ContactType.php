<?php

namespace Modules\User\Enums;

enum ContactType :string{
    case Email='email';
    case Phone='phone';

    public static function detectContactType($contact): ContactType
    {
        if (filter_var($contact,FILTER_VALIDATE_EMAIL)){
            return self::Email;
        }
        return  self::Phone;
    }
}
