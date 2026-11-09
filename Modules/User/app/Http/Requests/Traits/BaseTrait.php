<?php

namespace Modules\User\Http\Requests\Traits;

trait BaseTrait
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

}
