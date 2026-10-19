@extends('layout.admin-panel')
@section('content')
    <h2>کتاب</h2>
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        {{-- Header --}}
        <div class="card-header bg-white border-0 p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1 fw-bold">لیست کتاب ها</h5>
                    <p class="text-muted mb-0 small">
                        کتاب هایی که در سایت ما به فروش میرسند
                    </p>
                </div>

                <a href="{{route('book.create')}}" class="btn btn-primary rounded-3 px-4">
                    <i class="bi bi-plus-lg me-1"></i>
                    اضافه کردن کتاب جدید
                </a>
            </div>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                <tr>
                    <th class="ps-4">Id</th>
                    <th>اسم کتاب </th>
                    <th>توضیحات کتاب</th>
                    <th>ISBN </th>
                    <th>قیمت</th>
                    <th>موجودی</th>
                    <th>نام نویسنده</th>
                    <th>نام انتشارات</th>
                    <th class="text-end pe-4">اقدامات</th>
                </tr>
                </thead>

                <tbody>
                @foreach($books as $book)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                    {{$book->id}}
                                </div>
                            </div>
                        </td>

                        <td class="text-muted">{{$book->name}}</td>

                        <td><span class="badge text-bg-light border">{!! Str::limit($book->description, 50) !!}</span></td>

                        <td class="text-muted">{{$book->ISBN}}</td>

                        <td class="text-muted">{{$book->price}}</td>
                        <td class="text-muted">{{$book->stock}}</td>
                        <td class="text-muted">{{$book->authors->first()?->name}}</td>
                        <td class="text-muted">{{$book->publisher->name}}</td>
                        <td>
                            <div class="d-flex mx-2">
                                <a href="{{route('book.edit', $book->id)}}" class="btn btn-sm btn-outline-primary"> ویرایش</a>
                                <form action="{{route('book.destroy', $book->id)}}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm">حذف</button>
                                </form>
                                <a href="{{route('book.gallery', $book->id)}}" class="btn btn-sm btn-outline-primary"> میدریت عکس ها</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{-- Footer / Pagination --}}
        <div class="d-flex justify-content-between align-items-center p-4 border-top">

            <small class="text-muted">
                نمایش کتاب ها
            </small>

            <nav>
                <div class="pagination pagination-sm mb-0">
                </div>
            </nav>

        </div>
    </div>
@endsection
