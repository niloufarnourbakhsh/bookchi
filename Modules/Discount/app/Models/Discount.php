<?php

namespace Modules\Discount\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Book\Models\Book;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Book\Models\Category;

class Discount extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['code','percent','expired_at'];

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}
