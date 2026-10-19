<?php

namespace Modules\Book\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Book\Http\Requests\StoreBookRequest;
use Modules\Book\Http\Requests\UpdateBookRequest;
use Modules\Book\Models\Book;
use Modules\Book\Services\BookService;
use Modules\UploadFile\Enums\DiskType;
use Modules\UploadFile\Enums\FileType;
use Modules\UploadFile\Services\FileService;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query=Book::query()->with(['contributors','publisher','authors']);
        if ($keyword=request('search')){
            $query->where(function ($query) use($keyword){
                $query->where('name','like',"%{$keyword}%")
                    ->orWhere('description','like',"%{$keyword}%");
            });
        }
        $books=$query->get();
        return view('book::admin.book.index')->with('books', $books);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('book::admin.book.create');
    }
    public function store(StoreBookRequest $request, BookService $bookService) {
        $book=$bookService->CreateBook($request->validated());
        collect($request->file('file'))->each(function ($item) use($book){
            FileService::make(DiskType::Public)
                ->uploadFile(file: $item, type: FileType::book, directory: '/book/'.$book->name, relatedModel: $book);
        });
        return redirect()->route('book.index');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        return view('book::admin.book.edit')->with('book', $book);
    }

    /**
     * Update the specified resource in storage.
     * @throws \Throwable
     */
    public function update(UpdateBookRequest $request, Book $book, BookService $bookService)
    {
        $bookService->updateBook($book, $request->validated());
        return redirect()->route('book.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->back();
    }
}
