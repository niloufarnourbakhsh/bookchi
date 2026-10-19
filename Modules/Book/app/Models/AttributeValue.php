<?php

namespace Modules\Book\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttributeValue extends Model
{

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['value'];

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

}
