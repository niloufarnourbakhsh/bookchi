<?php

namespace Modules\UploadFile\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\UploadFile\Enums\DiskType;
use Modules\UploadFile\Enums\FileType;
use Modules\UploadFile\Models\File;
use Exception;

class FileService
{
    public function __construct(public DiskType $disk)
    {
    }

    public static function make(DiskType $diskType): FileService
    {
        return new self($diskType);
    }

    /**
     * @throws Exception
     */
    public function uploadFile(UploadedFile $file, FileType $type, string $directory, ?Model $relatedModel, ?string $fileName=null, ?bool $replaceExists=false): Model
    {
        [$extension, $path, $mainName ] = $this->prepareFile($file,$relatedModel, $fileName, $directory, $replaceExists);

        return $this->storeDataInDB(model: $relatedModel,mainName:  $mainName,extension:  $extension,file:  $file,path:  $path ,type: $type  );
    }

    /**
     * @throws Exception
     */
    public function deleteFile(array|string $paths, Model $model):bool
    {
        $query = $this->queryBuilder($model);
        return $query->where('disk', $this->disk->value)
            ->whereIn('path', (array)$paths)
            ->each(function (File $file) {
                    $file->delete();
            });

    }
    protected function queryBuilder(?Model $relatedModel)
    {
        if ($relatedModel  && !method_exists($relatedModel, 'files'))
                throw  new Exception('the file method does not exists');
       return $relatedModel ? $relatedModel->files() : File::query() ;
    }

    /**
     * @throws Exception
     */
    public function storeDataInDB(?Model $model, string $mainName, ?string $extension, UploadedFile $file, false|string $path ,FileType $type): Model
    {
        $query=$this->queryBuilder($model);
        return $query->create([
            'file_name' => $mainName,
            'extension' => $extension,
            'disk' => $this->disk->value,
            'mime_type' => $file->getMimeType(),
            'path' => $path ,
            'type'=>$type->value
        ]);
    }

    /**
     * @param UploadedFile $file
     * @param string|null $fileName
     * @param string $directory
     * @param bool $replaceExists
     * @return array
     * @throws Exception
     */
    protected function prepareFile(UploadedFile $file,?Model $model, ?string $fileName, string $directory, bool $replaceExists): array
    {
        $extension = $file->getClientOriginalExtension();
        if (!$fileName) {
            $path = Storage::disk($this->disk->value)->putFile($directory, $file);
                if (!$path) throw  new Exception('the store operation in storage has failed ');
            $mainName = basename($path);
            $baseName = pathinfo($mainName, PATHINFO_FILENAME);
        } else {
            $baseName = pathinfo($fileName, PATHINFO_FILENAME);
            $mainName = $baseName . '.' . $extension;
            $path = "$directory/$mainName";
            if (Storage::disk($this->disk->value)->exists($path)) {
                if ($replaceExists){
                    $this->deleteFile($path,$model);
                }else{
                    $mainName = $baseName . '-' . Str::random(6) . '.' ;
                }
            }
            $path = Storage::disk($this->disk->value)->putFileAs($directory, $file, $mainName);
            if (!$path) throw  new Exception('the store operation in storage has failed ');
        }
        return [$extension, $path, $baseName];
    }
}
