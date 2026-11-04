<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\User\Http\Requests\RegisterRequest;
use Modules\User\Models\User;

class UserController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user=User::query()->create($request->validated());
        $token=$user->createToken( name: 'api_token' ,
            expiresAt: now()->addDays(60))->plainTextToken;
        return $token;
    }
}
