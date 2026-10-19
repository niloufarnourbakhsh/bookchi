@extends('layout.admin-panel')
@section('content')

    <h2>ایجاد</h2>
    <form action="{{route('categories.store')}}" method="post">
        @csrf
        <div class="mt-2">
            <label for="name">نام دسته بندی  </label>
            <input type="text" name="name" class="form-control" id="name">
            @if($errors->has('name'))
                <span class="alert alert-danger">{{$errors->first('name')}}</span>
            @endif
        </div>
        <button class="btn btn-primary mt-2" type="submit">ثبت</button>
    </form>
@endsection

