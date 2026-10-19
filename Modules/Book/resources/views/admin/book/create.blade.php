@extends('layout.admin-panel')
@section('content')

    <form action="{{route('book.store')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="mt-2">
            <label for="name">نام کتاب </label>
            <input type="text" name="name" class="form-control" id="name" value="{{old('name')}}">
            @if($errors->has('name'))
                <span class="alert alert-danger">{{$errors->first('name')}}</span>
            @endif
        </div>
        <div class="mt-2">
            <label for="ISBN">شماره کتاب </label>
            <input type="text" name="ISBN" class="form-control" id="ISBN" value="{{old('ISBN')}}">
            @if($errors->has('ISBN'))
                <span class="alert alert-danger">{{$errors->first('ISBN')}}</span>
            @endif
        </div>
        <div class="mt-2">
            <label for="description">توضیحات کتاب </label>
            <textarea style="height: 200px;" name="description" class="form-control"
                      id="description">{{old('description')}}</textarea>
            @if($errors->has('name'))
                <span class="alert alert-danger">{{$errors->first('description')}}</span>
            @endif
        </div>
        <div class="mt-2">
            <label for="price">قیمت</label>
            <input type="text" name="price" id="price" class="form-control" value="{{old('price')}}">
            @if($errors->has('price'))
                <span class="alert alert-danger">{{$errors->first('price')}}</span>
            @endif
        </div>
        <div class="mt-2">
            <label for="stock">تعداد موجودی </label>
            <input type="text" name="stock" id="stock" class="form-control" value="{{old('stock')}}">
            @if($errors->has('stock'))
                <span class="alert alert-danger">{{$errors->first('stock')}}</span>
            @endif
        </div>
        <div class="mt-3">
            <label for="category">دسته بندی</label>
            <select class="form-control" id="category" name="category_id[]" multiple>
                @foreach($categories as $category)
                    <option value="{{$category->id}}">{{$category->name}} </option>
                @endforeach
                @if($errors->has('category_id'))
                    <span class="alert alert-danger">{{$errors->first('category_id')}}</span>
                @endif
            </select>
        </div>
        <div class="mt-3">
            <label for="author">نویسنده </label>
            <select class="form-control" id="author" name="author_id">
                @foreach($contributors as $contributor)
                    <option value="{{$contributor->id}}">{{$contributor->name}} </option>
                @endforeach
            </select>
            @if($errors->has('author_id'))
                <span class="alert alert-danger">{{$errors->first('author_id')}}</span>
            @endif
        </div>
        <div class="mt-3">
            <label for="translator">مترجم </label>
            <select class="form-control" id="translator" name="translator_id">
                <option value="">بدون مترجم</option>
            @foreach($contributors as $contributor)
                    <option value="{{$contributor->id}}">{{$contributor->name}} </option>
                @endforeach
            </select>
            @if($errors->has('translator_id'))
                <span class="alert alert-danger">{{$errors->first('translator_id')}}</span>
            @endif
        </div>
        <div class="mt-3">
            <label for="publisher">انتشارات</label>
            <select class="form-control" id="publisher" name="publisher_id">
                @foreach($publishers as $publisher)
                    <option value="{{$publisher->id}}">{{$publisher->name}} </option>
                @endforeach
                @if($errors->has('publisher_id'))
                    <span class="alert alert-danger">{{$errors->first('publisher_id')}}</span>
                @endif
            </select>
        </div>
        <div class="form-control">
            <label for="photo">تصاویر</label>
            <input type="file" name="file[]" id="photo" accept="image/*"
                   multiple class="form-control ">
            @if($errors->first('file'))
                <p class="fw-bolder text-danger">{{$errors->first('file')}}</p>
            @endif
        </div>
        <div class="card border-0 shadow-sm rounded-4 mb-4">

            <div class="card-header bg-white border-0 p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1 fw-bold">مشخصات کتاب</h5>
                        <small class="text-muted">ویژگی‌ها و مقادیر کتاب را اضافه کنید</small>
                    </div>

                    <button type="button" id="add-book-attribute" class="btn btn-primary">
                        + افزودن ویژگی
                    </button>
                </div>
            </div>

            <div class="card-body p-4">

                <div id="book-attribute-section">

                    {{-- ردیف‌های ویژگی اینجا ساخته می‌شوند --}}
                </div>
            </div>

        </div>
        <button class="btn btn-dark mt-2" type="submit">ذخیره</button>

    </form>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            let attributeIndex = 0;

            const addButton = document.getElementById('add-book-attribute');
            const attributeSection = document.getElementById('book-attribute-section');

            addButton.addEventListener('click', function () {

                let row = ` <div class="row g-3 align-items-end mb-3"  id="attribute-row-${attributeIndex}">
                    {{-- Attribute --}}
                <div class="col-md-5">
                    <label class="form-label">
                        ویژگی
                    </label>

                    <select
                        name="attributes[${attributeIndex}][attribute]"
                            class="form-select attribute-select"
                        >

                            <option value="">
                                انتخاب یا ایجاد ویژگی
                            </option>

                            @foreach($attributes as $attribute)

                <option value="{{ $attribute->name }}">
                                    {{ $attribute->name }}
                </option>

@endforeach

                </select>

            </div>


{{-- Value --}}
                <div class="col-md-5">

                    <label class="form-label">
                        مقدار
                    </label>

                    <select
                        name="attributes[${attributeIndex}][value]"
                            class="form-select value-select"
                        >

                            <option value="">
                                انتخاب یا ایجاد مقدار
                            </option>

                        </select>

                    </div>


                    {{-- Delete --}}
                <div class="col-md-2">

                    <button
                        type="button"
                        class="btn btn-outline-danger w-100"
                        onclick="removeAttributeRow(${attributeIndex})"
                        >
                            حذف
                        </button>

                    </div>

                </div>
            `;


                attributeSection.insertAdjacentHTML('beforeend', row);


                /*
                |--------------------------------------------------------------------------
                | Select2 - Attribute
                |--------------------------------------------------------------------------
                */

                $(`#attribute-row-${attributeIndex} .attribute-select`).select2({
                    tags: true,
                    width: '100%',
                    placeholder: 'انتخاب یا ایجاد ویژگی'
                });


                /*
                |--------------------------------------------------------------------------
                | Select2 - Value
                |--------------------------------------------------------------------------
                */

                $(`#attribute-row-${attributeIndex} .value-select`).select2({
                    tags: true,
                    width: '100%',
                    placeholder: 'انتخاب یا ایجاد مقدار'
                });


                attributeIndex++;

            });


            /*
            |--------------------------------------------------------------------------
            | حذف ردیف
            |--------------------------------------------------------------------------
            */

            window.removeAttributeRow = function (index) {

                const row = document.getElementById(`attribute-row-${index}`);

                if (row) {
                    row.remove();
                }

            };

        });
    </script>
@endsection

