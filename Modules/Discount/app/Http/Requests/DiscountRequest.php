<?php

namespace Modules\Discount\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DiscountRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $discountId = request()->route('discount');
        return [
            'code'=>['required',Rule::unique('discounts','code')->ignore($discountId)],
            'percent'=>['required','integer','between:1,99'],
            'book_id'=>['nullable','array','exists:books,id'],
            'category_id'=>['nullable','array','exists:categories,id'],
            'expired_at'=>['nullable']
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
