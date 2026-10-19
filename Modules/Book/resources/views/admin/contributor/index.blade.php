@extends('layout.admin-panel')
@section('content')
    <h2>نویسندگان</h2>
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        {{-- Header --}}
        <div class="card-header bg-white border-0 p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1 fw-bold">لیست نویسندگان</h5>
                    <p class="text-muted mb-0 small">
                        نویسندگانی هایی که آثارشان در سایت ما   فروش دارند.
                    </p>
                </div>

                <a href="{{route('contributors.create')}}" class="btn btn-primary rounded-3 px-4">
                    <i class="bi bi-plus-lg me-1"></i>
                    اضافه کردن نویسنده جدید
                </a>
            </div>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                <tr>
                    <th class="ps-4">Id</th>
                    <th>اسم</th>
                    <th>درباره ی  نویسنده و مترجم</th>
                    <th>تصویر</th>
                    <th class="text-end pe-4">اقدامات</th>
                </tr>
                </thead>

                <tbody>
                @foreach($contributors as $contributor)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                    {{$contributor->id}}
                                </div>
                            </div>
                        </td>

                        <td class="text-muted">{{$contributor->name}}</td>

                        <td><span class="badge text-bg-light border">{!! Str::limit($contributor->bio, 50) !!}</span></td>

                        <td class="text-muted"></td>
                        <td>
                            <div class="d-flex mx-2">
                                <a href="{{route('contributors.edit', $contributor->id)}}" class="btn btn-sm btn-outline-primary"> ویرایش</a>
                                <form action="{{route('contributors.destroy', $contributor->id)}}" method="post">
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
                نمایش نویسندگان
            </small>

            <nav>
                <div class="pagination pagination-sm mb-0">
                </div>
            </nav>

        </div>
    </div>
@endsection
