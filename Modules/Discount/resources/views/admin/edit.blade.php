@extends('layout.admin-panel')
@section('content')

    <h3> ویرایش تخفیف جدید </h3>
    <form action="{{route('discounts.update', $discount->id)}}" method="post">
        @csrf
        @method('PUT')
        <div class="mt-2">
            <label for="code">نام تخفیف</label>
            <input type="text" name="code" class="form-control" value="{{$discount->code}}" id="code">
            @if($errors->has('code'))
                <span class="alert alert-danger">{{$errors->first('code')}}</span>
            @endif
        </div>
        <div class="mt-2">
            <label for="percent">درصد تخفیف</label>
            <input type="text" name="percent" class="form-control" id="percent" value="{{$discount->percent}}">
            @if($errors->has('percent'))
                <span class="alert alert-danger">{{$errors->first('percent')}}</span>
            @endif
        </div>
        <div class="mt-3">
            <label for="books">کتاب ها </label>
            <select class="form-control" id="book" name="book_id[]" multiple>
                @foreach($books as $book)
                    <option value="{{$book->id}}"
                        {{ in_array($book->id, old('book_id', $discount->books->pluck('id')->toArray())) ? 'selected' : '' }}
                    >{{$book->name}} </option>
                @endforeach
                @if($errors->has('book_id'))
                    <span class="alert alert-danger">{{$errors->first('book_id')}}</span>
                @endif
            </select>
        </div>
        <div class="mt-3">
            <label for="category">دسته بندی</label>
            <select class="form-control" id="category" name="category_id[]" multiple>
                @foreach($categories as $category)
                    <option value="{{$category->id}}"
                        {{ in_array($category->id, old('category_id', $discount->categories->pluck('id')->toArray())) ? 'selected' : '' }}
                    >{{$category->name}} </option>
                @endforeach
                @if($errors->has('category_id'))
                    <span class="alert alert-danger">{{$errors->first('category_id')}}</span>
                @endif
            </select>
        </div>
        <div class="form-group">
            <label for="expired_at" class="col-sm-2 control-label">مهلت استفاده</label>
            <input type="datetime-local" name="expired_at" class="form-control" id="expired_at" placeholder="ملهت استفاده را وارد کنید"
            value="{{ old('expired_at', \Carbon\Carbon::parse($discount->expired_at)->format('Y-m-d\TH:i:s')) }}">
            @if($errors->has('expired_at'))
                <span class="alert alert-danger">{{$errors->first('expired_at')}}</span>
            @endif
        </div>
        <button class="btn btn-primary mt-2" type="submit">ثبت</button>
    </form>
@endsection

