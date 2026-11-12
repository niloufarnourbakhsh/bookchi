<?php

namespace Modules\User\Services;

use Illuminate\Support\Facades\Cache;

class TokenService
{
    const string token_prefix="verification:token:";
    const int token_expiry_time=5;
    public function putToken($token, $validatedData , $contactType): void
    {
        $expiryTime=now()->addMinutes(self::token_expiry_time);;
        Cache::put(self::token_prefix.$token,[
            'token'=>$token,
            'contact'=>$validatedData['contact'],
            'action'=>$validatedData['action'] ,
            'contact_type'=>$contactType,
        ], $expiryTime);
    }

    public function getToken($token): mixed
    {
        return Cache::pull(self::token_prefix.$token);
    }
}
