<?php

namespace Modules\Book\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Book\Models\Book;
use Modules\Book\Models\Category;

class CatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query=Book::query()->with('categories');
        if ($category=request('category')){
            $query->whereHas('categories',function ($query)use($category){
                $query->where('name',$category);
            });
        }
       $books= $query->paginate(20);
        $categories=Category::all();
        return view('book::gallery')->with('books',$books)->with('categories',$categories);
    }
    public function show(Book $book)
    {
        $book->with(['files','publisher','translator','authors','attributes.values']);
        return view('book::show')->with('book',$book);
    }

}
