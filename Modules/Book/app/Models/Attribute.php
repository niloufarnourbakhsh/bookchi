<?php

namespace Modules\Book\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name', 'value'];

    public function values()
    {
        return $this->hasMany(AttributeValue::class);
    }
}
