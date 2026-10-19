<?php

namespace Modules\Book\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\UploadFile\Models\File;

class Publisher extends Model
{

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name','description','website'];

    public function files(): MorphMany
    {
        return $this->morphMany(File::class,'fileable');
    }
}
