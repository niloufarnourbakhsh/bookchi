<?php

namespace Modules\Book\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\UploadFile\Models\File;

class Contributor extends Model
{

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name','bio'];

    public function Books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class)->withPivot('role');
    }

    public function files(): MorphMany
    {
        return $this->morphMany(File::class,'fileable');
    }
}
