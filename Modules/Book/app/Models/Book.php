<?php

namespace Modules\Book\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Book\Enums\ContributorRoleEnum;
use Modules\UploadFile\Models\File;


class Book extends Model
{

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name','ISBN','description','price','stock','author_id','publisher_id'];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function contributors(): BelongsToMany
    {
        return $this->belongsToMany(Contributor::class)->withPivot('role');
    }

    public function authors(): BelongsToMany
    {
        return $this->contributors()->wherePivot('role',ContributorRoleEnum::author);
    }
    public function translator(): BelongsToMany
    {
        return $this->contributors()->wherePivot('role',ContributorRoleEnum::translator);
    }
    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class)->withPivot('value_id');
    }

    public function files(): MorphMany
    {
        return $this->morphMany(File::class,'fileable');
    }
}
