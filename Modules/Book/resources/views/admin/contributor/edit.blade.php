@extends('layout.admin-panel')
@section('content')

    <h2>ویرایش</h2>
    <form action="{{route('contributors.update', $contributor->id)}}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mt-2">
            <label for="name">نام  نویسنده و مترجم </label>
            <input type="text" name="name" class="form-control" id="name" value="{{old('name', $contributor->name)}}">
            @if($errors->has('name'))
                <span class="alert alert-danger">{{$errors->first('name')}}</span>
            @endif
        </div>
        <div class="mt-2">
            <label for="name">درباره ی  نویسنده و مترجم </label>
            <textarea name="bio" class="form-control">{{$contributor->bio}}</textarea>
            @if($errors->has('bio'))
                <span class="alert alert-danger">{{$errors->first('bio')}}</span>
            @endif
        </div>
        <div class="form-control">
            <label for="photo">تصاویر</label>
            <input type="file" name="photo" id="photo" class="form-control"  accept="image/*">
            @if($errors->first('photo'))
                <p class="fw-bolder text-danger">{{$errors->first('photo')}}</p>
            @endif
        </div>
        <button class="btn btn-primary mt-2" type="submit">ثبت</button>
    </form>
@endsection

