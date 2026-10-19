@extends('layout.admin-panel')
@section('content')

    <h2>ایجاد</h2>
    <form action="{{route('contributors.store')}}" method="post" enctype="multipart/form-data" >
        @csrf
        <div class="mt-2">
            <label for="name">نام نویسنده و مترجم </label>
            <input type="text" name="name" class="form-control" id="name">
            @if($errors->has('name'))
                <span class="alert alert-danger">{{$errors->first('name')}}</span>
            @endif
        </div>
        <div class="mt-2">
            <label for="name">درباره ی نویسنده و مترجم </label>
            <textarea name="bio" class="form-control"></textarea>
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

