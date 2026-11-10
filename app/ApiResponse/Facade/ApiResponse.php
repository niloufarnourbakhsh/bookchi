<?php

namespace App\ApiResponse\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\ApiResponse\ApiResponseBuilder withMessage(string $message)
 * @method static \App\ApiResponse\ApiResponseBuilder withToken(string $token)
 * @method static \App\ApiResponse\ApiResponseBuilder withData(array $data)
 * @method static \App\ApiResponse\ApiResponseBuilder withStatus(int $status)
 * @method static \App\ApiResponse\ApiResponseBuilder withAppend(mixed $append)
 * @method static \App\ApiResponse\ApiResponseBuilder build(mixed $append)
 * @method static  \App\ApiResponse\ApiResponse response(mixed $append)
 *
 * @see \App\ApiResponse\ApiResponseBuilder
 */
class ApiResponse extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'ApiResponse';
    }
}
