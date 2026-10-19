@extends('layout.app')
@section('base-page')
    <div class="container-fluid p-3 bg-primary ">
        <div class="container d-flex justify-content-around">
            <div><h5 class="text-white"> پنل مدیریت</h5></div>
            <div>

            </div>
        </div>
    </div>
    <div class="container my-3 ">
        <div class="row">
            <div class="col-2">
                <div class="d-flex" dir="rtl">
                    {{-- Sidebar --}}
                    <aside class="bg-dark text-white vh-100 position-fixed top-0 end-0" style="width: 260px;">
                        {{-- Logo --}}
                        <div class="px-4 py-4 border-bottom border-secondary">
                            <h4 class="mb-0 fw-bold">
                                مدیریت<span class="text-primary"> پنل</span>
                            </h4>
                        </div>

                        {{-- Menu --}}
                        <nav class="p-3">
                            <small class="text-secondary px-3">منوی اصلی</small>
                            <ul class="nav nav-pills flex-column gap-1 mt-3">

                                {{-- Dashboard --}}
                                <li class="nav-item">
                                    <a href="#" class="nav-link active rounded-3 d-flex align-items-center gap-3">
                                        <span>▦</span>
                                        داشبورد
                                    </a>
                                </li>

                                {{-- Users --}}
                                <li class="nav-item">
                                    <a href="#" class="nav-link text-white rounded-3 d-flex align-items-center gap-3">
                                        <span>♙</span>
                                        مدیریت کاربرها
                                    </a>
                                </li>

                                {{-- Products --}}
                                <li class="nav-item">
                                    <a href="{{route('book.index')}}" class="nav-link text-white rounded-3 d-flex align-items-center gap-3">
                                        <span>▣</span>
                                        مدیریت کتابها
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('categories.index')}}" class="nav-link text-white rounded-3 d-flex align-items-center gap-3">
                                        <span>▣</span>
                                        مدیریت دسته بندی ها
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('contributors.index')}}" class="nav-link text-white rounded-3 d-flex align-items-center gap-3">
                                        <span>▣</span>
                                        مدیریت مترجم و نویسنده ها
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('publisher.index')}}" class="nav-link text-white rounded-3 d-flex align-items-center gap-3">
                                        <span>▣</span> انتشارات
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </aside>

                </div>
            </div>
            <div class="col-9 p-3">
                @yield('content')
            </div>
            <div class="col-1">
            </div>
        </div>
    </div>
@endsection
