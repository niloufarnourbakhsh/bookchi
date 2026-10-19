@extends('layout.admin-panel')
@section('content')

    <h2>ویرایش</h2>
    <form action="{{route('categories.update', $category->id)}}" method="post">
        @csrf
        @method('PUT')
        <div class="mt-2">
            <label for="name">نام دسته بندی  </label>
            <input type="text" name="name" class="form-control" id="name" value="{{old('name', $category->name)}}">
            @if($errors->has('name'))
                <span class="alert alert-danger">{{$errors->first('name')}}</span>
            @endif
        </div>
        <button class="btn btn-primary mt-2" type="submit">ثبت</button>
    </form>
@endsection

