<?php

namespace Modules\User\Services;

use Illuminate\Support\Facades\Cache;
use Modules\User\Enums\ActionType;
use Modules\User\Enums\ContactType;

class CacheService
{
    const int EmailExpiryTime=3;
    const int PhoneExpiryTime=2;
    /**
     * @param ActionType $actionType
     * @param string $contact
     * @return string
     */
    public function getCacheKey(ActionType $actionType, string $contact): string
    {
        $key=hash('sha256', $actionType->value . ':' . $contact );
        return 'verification:' .$key;
    }

    public function putCache(int $code,$actionType ,$contactType, $contact ,$expireTime): void
    {
        if (is_null($expireTime)){
            $expireTime= $contactType===ContactType::Email  ? self::EmailExpiryTime : self::PhoneExpiryTime;
        }
        $expireAT=now()->addMinutes($expireTime);
        Cache::put($this->getCacheKey($actionType, $contact),[
            'action'=>$actionType->value,
            'contact'=>$contact,
            'code'=>$code,
            'expired_at'=>$expireAT->timestamp
        ],$expireAT);
    }

    public function getCache($actionType , $contact)
    {
        return Cache::get($this->getCacheKey($actionType, $contact));
    }
}
