<?php

namespace Modules\User\Http\Controllers;


use Illuminate\Routing\Controller;
use Modules\User\Enums\ContactType;
use Modules\User\Http\Requests\CheckUserRequest;
use Modules\User\Http\Requests\SendVerificationRequest;
use Modules\User\Http\Requests\VerifyCodeRequest;
use Modules\User\Models\User;
use Modules\User\Services\VerificationCodeService;

class VerifyController extends Controller {
    public function __construct(public VerificationCodeService $codeService)
    {

    }

    public function checkUser(CheckUserRequest $request)
    {
        $user=User::query()->where('email', $request->contact)
        ->orWhere('phone', $request->contact)->exists();
        if ($user){
            return response()->json([
                'message'=>'کاربر موجود است'
            ]);
        }
        return  response()->json([
            'message'=>'کاربر یافت نشد.'
        ]);
    }

    public function sendCode(SendVerificationRequest $request)
    {
        $code=$this->codeService->CreateCode(actionType: $request->actionType,
            contact: $request->contact,
            contactType:$request->contactType );
        $codeStatus=false;
        if ($request->contactType===ContactType::Email){
            $codeStatus=  $this->codeService->sendCodeByEmail(code: $code,contact:  $request->contact);
        }else{
            $codeStatus= $this->codeService->sendCodeBySMS(code: $code,contact:  $request->contact);
        }
        if (!$codeStatus){
            return response()->json([
                'message'=>'ارسال کد به مشکل برخورد'
            ]);
        }
        return response()->json([
            'message'=>'کد یکبار مصرف برای شما ارسال شد'
        ]);
    }

    public function verifyCode(VerifyCodeRequest $request)
    {
        return $this->codeService->generateToken($request);
    }
}
