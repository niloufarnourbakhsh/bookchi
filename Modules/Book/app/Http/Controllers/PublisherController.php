<?php

namespace Modules\Book\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Book\Http\Requests\StorePublisherRequest;
use Modules\Book\Http\Requests\UpdatePublisherRequest;
use Modules\Book\Models\Publisher;
use Modules\UploadFile\Enums\DiskType;
use Modules\UploadFile\Enums\FileType;
use Modules\UploadFile\Services\FileService;

class PublisherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query=Publisher::query();
        if ($publisher=request('publisher')){
            $query->where('name','like',"%$publisher%");
        }
        $publishers=$query->paginate(15);
        return view('book::admin.publisher.index')->with('publishers',$publishers);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('book::admin.publisher.create');
    }

    /**
     * Store a newly created resource in storage.
     * @throws \Exception
     */
    public function store(StorePublisherRequest $request) {
        $publisher=Publisher::query()->create($request->validated());
        FileService::make(DiskType::Public)
            ->uploadFile(file: $request->file('photo'),
                type: FileType::Logo,
                directory: 'book/logo',
                relatedModel: $publisher,
                fileName: $publisher->name,
                replaceExists: true);
        return redirect()->route('publisher.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Publisher $publisher)
    {
        return view('book::admin.publisher.edit')->with('publisher', $publisher);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePublisherRequest $request, Publisher $publisher)
    {
        $publisher->update($request->validated());
        if ($request->has('photo')){
            FileService::make(DiskType::Public)
                ->uploadFile(file: $request->file('photo'),
                    type: FileType::Logo,
                    directory: 'book/logo',
                    relatedModel: $publisher,
                    fileName: $publisher->name,
                    replaceExists: true);
        }
        return  redirect()->route('publisher.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publisher $publisher) {
        $publisher->delete();
       return redirect()->back();
    }
}
