@extends('layout.admin-panel')
@section('content')
    <form action="{{route('publisher.store')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div  class="mt-2">
            <label for="name">نام انتشارات </label>
            <input type="text" name="name" class="form-control" id="name">
            @if($errors->has('name'))
                <span class="alert alert-danger">{{$errors->first('name')}}</span>
            @endif
        </div>
        <div  class="mt-2">
            <label for="description"> معرفی انتشارات </label>
            <textarea name="description" class="form-control" style="height: 200px;"></textarea>
            @if($errors->has('description'))
                <span class="alert alert-danger">{{$errors->first('description')}}</span>
            @endif
        </div>
        <div  class="mt-2">
            <label for="website"> آدرس وبسایت </label>
            <input type="text" name="website" class="form-control" id="website">
            @if($errors->has('website'))
                <span class="alert alert-danger">{{$errors->first('website')}}</span>
            @endif
        </div>
        <div class="form-control">
            <label for="photo">تصاویر</label>
            <input type="file" name="photo" id="photo" class="form-control"  accept="image/*">
            @if($errors->first('photo'))
                <p class="fw-bolder text-danger">{{$errors->first('photo')}}</p>
            @endif
        </div>
        <button class="btn btn-primary" type="submit">ثبت</button>
    </form>
@endsection

