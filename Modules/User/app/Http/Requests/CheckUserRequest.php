<?php

namespace Modules\User\Http\Requests;

use Closure;
use Illuminate\Foundation\Http\FormRequest;

class CheckUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'contact' => ['bail', 'required', 'string',
                function (string $attribute, mixed $value, Closure $fail) {
                    if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $validator = Validator(['email' => $value], ['email' => 'email:rfc,dns']);
                        if ($validator->fails()) {
                            $fail('ایمیل معتبر نیستت');
                        }
                        return;
                    }
                    $validator = Validator(['phone' => $value], ['phone' => 'phone:mobile,IR']);
                    if ($validator->fails()) {
                        $fail('شماره تماس وارد شده معتبر نیست');
                    }
                }]
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
