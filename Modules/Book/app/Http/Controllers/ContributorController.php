<?php

namespace Modules\Book\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Book\Http\Requests\ContributorRequest;
use Modules\Book\Models\Contributor;
use Modules\UploadFile\Enums\DiskType;
use Modules\UploadFile\Enums\FileType;
use Modules\UploadFile\Services\FileService;

class ContributorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query=Contributor::query();
        if ($contributor=request('contributor')){
            $query->where('name'.'like',"%$contributor%");
        }
        $contributors=$query->paginate('15');
        return view('book::admin.contributor.index')->with('contributors', $contributors);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('book::admin.contributor.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContributorRequest $request)
    {
       $contributor =Contributor::query()->create($request->validated());
       if ($request->has('photo')){
           FileService::make(DiskType::Public)
               ->uploadFile(file: $request->file('photo'),
                   type: FileType::profile,
                   directory: 'book/profile',
                   relatedModel: $contributor,
                   fileName: $contributor->name,
                   replaceExists: true);
       }
        return redirect()->route('contributors.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contributor $contributor)
    {
        return view('book::admin.contributor.edit')->with('contributor',$contributor);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContributorRequest $request, Contributor $contributor)
    {
        $contributor->update($request->validated());
        if ($request->has('photo')){
            FileService::make(DiskType::Public)
                ->uploadFile(file: $request->file('photo'),
                    type: FileType::Logo,
                    directory: 'book/profile',
                    relatedModel: $contributor,
                    fileName: $contributor->name,
                    replaceExists: true);
        }
        return  redirect()->route('contributors.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contributor $contributor)
    {
        $contributor->delete();
        return redirect()->back();
    }
}
