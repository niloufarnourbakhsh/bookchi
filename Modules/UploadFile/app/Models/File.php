<?php

namespace Modules\UploadFile\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'file_name',
        'path',
        'extension',
        'disk',
        'mime_type',
        'path',
        'type'
    ];

    public static function boot()
    {
        parent::boot();
        static::deleting(function (File $file):bool {
            if (Storage::disk($file->disk)->exists($file->path)){
               $deleted= Storage::disk($file->disk)->delete($file->path);
               if (! $deleted){
                   return false;
               }
            }
            return true;
        });
    }
}
