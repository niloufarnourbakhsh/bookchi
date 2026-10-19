@extends('layout.admin-panel')
@section('content')
    <h2>دسته بندی </h2>
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        {{-- Header --}}
        <div class="card-header bg-white border-0 p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1 fw-bold">لیست تخفیف ها</h5>
                </div>

                <a href="{{route('discounts.create')}}" class="btn btn-primary rounded-3 px-4">
                    <i class="bi bi-plus-lg me-1"></i>
                    اضافه کردن تخفیف جدید
                </a>
            </div>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                <tr>
                    <th class="ps-4">Id</th>
                    <th>کد تخفیف</th>
                    <th>درصد </th>
                    <th>محصولات مرتبط</th>
                    <th>دسته بندی مرتبط</th>
                    <th class="text-end pe-4">اقدامات</th>
                </tr>
                </thead>

                <tbody>
                @foreach($discounts as $discount)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                    {{$discount->id}}
                                </div>
                            </div>
                        </td>

                        <td class="text-muted">{{$discount->code}}</td>

                        <td><span class="badge text-bg-light border">{{$discount->percent}}درصد </span> </td>
                        <td><span class="badge text-bg-light border">
                                {{$discount->books->pluck('name')->implode(',')}}
                            </span>
                        </td>
                        <td><span class="badge text-bg-light border">
                                {{$discount->categories->pluck('name')->implode(',')}}
                            </span>
                        </td>
                        <td><span class="badge text-bg-light border">{{$discount->percent}} </span> </td>

                        <td class="text-muted"></td>
                        <td>
                            <div class="d-flex mx-2">
                                <a href="{{route('discounts.edit', $discount->id)}}" class="btn btn-sm btn-outline-primary"> ویرایش</a>
                                <form action="{{route('discounts.destroy', $discount->id)}}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm">حذف</button>
                                </form>
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
                نمایش دسته بندی ها
            </small>

            <nav>
                <div class="pagination pagination-sm mb-0">
                </div>
            </nav>

        </div>
    </div>
@endsection
