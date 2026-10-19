<?php

namespace Modules\Book\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Book\Http\Requests\GalleryRequest;
use Modules\Book\Models\Book;
use Modules\UploadFile\Enums\DiskType;
use Modules\UploadFile\Enums\FileType;
use Modules\UploadFile\Models\File;
use Modules\UploadFile\Services\FileService;

class ManageBookPhotosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Book $book)
    {
        $photos=$book->files()->get();
        return view('book::admin.book.book-gallery')->with('photos', $photos)->with('book',$book);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GalleryRequest $request , Book $book)
    {
        collect($request->file('file'))->each(function ($item) use($book){
            FileService::make(DiskType::Public)
                ->uploadFile(file: $item, type: FileType::book, directory: '/book/'.$book->name, relatedModel: $book);
        });
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book, File $file) {
        $files=$book->files()->get();
        if ($files->count() > 1){
            FileService::make(DiskType::from($file->disk))->deleteFile(paths: $file->path,model:  $book);
            return redirect()->back();
        }
        Session::put('photo','شما حداقل یک عکس باید در جدول داشته باشید ');
        return redirect()->back();
    }
}
