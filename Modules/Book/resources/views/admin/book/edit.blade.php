@extends('layout.admin-panel')
@section('content')

    <form action="{{route('book.update',$book->id)}}" method="post">
        @csrf
        @method('PUT')
        <div class="mt-2">
            <label for="name">نام کتاب </label>
            <input type="text" name="name" class="form-control" id="name"  value="{{old('name',$book->name)}}">
            @if($errors->has('name'))
                <span class="alert alert-danger">{{$errors->first('name')}}</span>
            @endif
        </div>
        <div class="mt-2">
            <label for="ISBN">شماره کتاب </label>
            <input type="text" name="ISBN" class="form-control" id="ISBN"  value="{{old('ISBN', $book->ISBN)}}">
            @if($errors->has('ISBN'))
                <span class="alert alert-danger">{{$errors->first('ISBN')}}</span>
            @endif
        </div>
        <div class="mt-2">
            <label for="description">توضیحات کتاب </label>
            <textarea style="height: 200px;" name="description" class="form-control" id="description">{{$book->description}}</textarea>
            @if($errors->has('description'))
                <span class="alert alert-danger">{{$errors->first('description')}}</span>
            @endif
        </div>
        <div class="mt-2">
            <label for="price">قیمت</label>
            <input type="text" name="price" id="price" class="form-control" value="{{old('price', $book->price)}}">
            @if($errors->has('price'))
                <span class="alert alert-danger">{{$errors->first('price')}}</span>
            @endif
        </div>
        <div class="mt-2">
            <label for="stock">تعداد موجودی </label>
            <input type="text" name="stock" id="stock" class="form-control" value="{{old('stock', $book->stock)}}">
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

        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>ویژگی‌های کتاب</strong>

                <button type="button" id="add-book-attribute" class="btn btn-primary">
                    + افزودن ویژگی
                </button>
            </div>

            <div class="card-body">
                <div id="book-attribute-section">

                    @foreach($book->attributes as $index => $attribute)

                        <div class="row g-3 align-items-end mb-3"
                             id="attribute-row-{{ $index }}">

                            <div class="col-md-5">
                                <label class="form-label">ویژگی</label>

                                <select
                                    name="attributes[{{ $index }}][attribute]"
                                    class="form-select attribute-select">

                                    <option value="{{ $attribute->name }}" selected>
                                        {{ $attribute->name }}
                                    </option>

                                    @foreach($attributes as $item)
                                        @if($item->id != $attribute->id)
                                            <option value="{{ $item->name }}">
                                                {{ $item->name }}
                                            </option>
                                        @endif
                                    @endforeach

                                </select>
                            </div>

                            <div class="col-md-5">
                                <label class="form-label">مقدار</label>

                                <select
                                    name="attributes[{{ $index }}][value]"
                                    class="form-select value-select">

                                    @foreach($attribute->values as $value)
                                        <option
                                            value="{{ $value->value }}"
                                            @selected($value->id == $attribute->pivot->value_id)>
                                            {{ $value->value }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="col-md-2">
                                <button
                                    type="button"
                                    class="btn btn-outline-danger w-100 remove-attribute">
                                    حذف
                                </button>
                            </div>

                        </div>

                    @endforeach

                </div>
            </div>
        </div>
        <button class="btn btn-dark mt-2" type="submit">ذخیره</button>

    </form>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            let attributeIndex = {{ $book->attributes->count() }};

            const section = document.getElementById('book-attribute-section');
            const addButton = document.getElementById('add-book-attribute');

            const attributesData = @json(
        $attributes->map(function ($attribute) {
            return [
                'name' => $attribute->name,
                'values' => $attribute->values->pluck('value')->values(),
            ];
        })->values()
    );

            function initializeSelects(row) {
                $(row).find('.attribute-select').select2({
                    tags: true,
                    width: '100%',
                    placeholder: 'انتخاب یا ایجاد ویژگی'
                });

                $(row).find('.value-select').select2({
                    tags: true,
                    width: '100%',
                    placeholder: 'انتخاب یا ایجاد مقدار'
                });
            }

            document.querySelectorAll('.attribute-select').forEach(function (select) {
                initializeSelects(select.closest('.row'));
            });

            // افزودن ردیف
            addButton.addEventListener('click', function () {

                const row = document.createElement('div');

                row.className = 'row g-3 align-items-end mb-3';
                row.id = `attribute-row-${attributeIndex}`;

                let options = '<option value=""></option>';

                attributesData.forEach(function (attribute) {
                    options += `
                <option value="${escapeHtml(attribute.name)}">
                    ${escapeHtml(attribute.name)}
                </option>
            `;
                });

                row.innerHTML = `
            <div class="col-md-5">
                <label class="form-label">ویژگی</label>
                <select name="attributes[${attributeIndex}][attribute]"
                        class="form-select attribute-select">
                    ${options}
                </select>
            </div>

            <div class="col-md-5">
                <label class="form-label">مقدار</label>
                <select name="attributes[${attributeIndex}][value]"
                        class="form-select value-select">
                    <option value=""></option>
                </select>
            </div>

            <div class="col-md-2">
                <button type="button"
                        class="btn btn-outline-danger w-100 remove-attribute">
                    حذف
                </button>
            </div>
        `;

                section.appendChild(row);
                initializeSelects(row);

                attributeIndex++;
            });

            // نمایش مقدارهای مربوط به ویژگی انتخاب‌شده
            $(document).on('change', '.attribute-select', function () {

                const name = $(this).val();
                const row = this.closest('.row');
                const valueSelect = $(row).find('.value-select');

                valueSelect.empty().append(new Option('', '', false, false));

                const attribute = attributesData.find(function (item) {
                    return item.name === name;
                });

                if (attribute) {
                    attribute.values.forEach(function (value) {
                        valueSelect.append(new Option(value, value, false, false));
                    });
                }

                valueSelect.trigger('change.select2');
            });

            // حذف ردیف از فرم
            $(document).on('click', '.remove-attribute', function () {
                const row = this.closest('.row');

                $(row).find('select').each(function () {
                    if ($(this).hasClass('select2-hidden-accessible')) {
                        $(this).select2('destroy');
                    }
                });

                row.remove();
            });

            function escapeHtml(value) {
                return String(value).replace(/[&<>"']/g, function (char) {
                    return {
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#039;'
                    }[char];
                });
            }

        });
    </script>
@endsection

