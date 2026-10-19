<?php

namespace Modules\Book\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name'=>['required','min:3','max:255'],
            'ISBN'=>['required','unique:books,ISBN'],
            'description'=>['required', 'min:50'],
            'price'=>['required','numeric'],
            'stock'=>['required', 'integer'],
            'publisher_id'=>['required','exists:publishers,id'],
            'category_id'=>['required','array','exists:categories,id'],
            'author_id' => ['required', 'exists:contributors,id'],
            'translator_id' => ['nullable', 'exists:contributors,id'],
            'file'=>['required','array','min:1'],
            'file.*'=>['required','image','mimes:jpg,jpeg,png,webp','max:3072'],
            'attributes'=>['nullable','array'],
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
