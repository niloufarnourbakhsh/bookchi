@extends('layout.admin-panel')
@section('content')
    <div class="container mt-4">
        <div class="row">
            <h6 class="col-6 bg-orange p-2 text-white">ویرایش تصاویر</h6>
        </div>
        <div class="row">
            <div class="col-6 align-self-end d-flex flex-column p-4">
                <form action="{{route('book.gallery.store',$book)}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-control">
                        <label for="photo">تصاویر</label>
                        <input type="file" name="file[]" id="photo" accept="image/*"
                               multiple class="form-control ">
                        @if($errors->first('file'))
                            <p class="fw-bolder text-danger">{{$errors->first('file')}}</p>
                        @endif
                    </div>
                    <div class="d-grid gap-2 ">
                        <button class="btn bg-success" type="submit">ذخیره</button>
                    </div>
                </form>

            </div>
            <div class="col-6 mt-3">
                <div class=" row row-cols-2">
                    @foreach($photos as $photo)
                        <div class="col my-1">
                            <div class="card">

                                <img src="{{url('/storage/'.$photo->path)}}" class="card-img-top" alt="..."
                                     style="max-height: 200px">
                                <div class="card-footer bg-delete">
                                    <form action="{{route('book.gallery.delete',[$book->id,$photo->id])}}" method="post">
                                        @method('DELETE')
                                        @csrf
                                        <div class="d-grid gap-2">
                                            <button class="btn bg-danger">delete</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>

@endsection
