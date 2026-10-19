@extends('layout.users-panel')
@section('content-part')
    <div class="container">
        <div class="row mb-5">
            <div class="col-4">
                <div class="row row-cols-1 p-3 m-3">
                    @foreach($book->files as $file)
                        <div class="col">
                            <div class="card toChoose">
                                <img src="{{url('/storage/'.$file->path)}}" alt="" class="my-3 img-style img"
                                     style="max-height: 200px">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-8">
                <div class="bg-light-green p-3 my-4 d-flex justify-content-between" >
                    <h5 class="fw-bolder ">{{$book->name}}</h5>
                    <form action="{{route('add.to.cart', $book->id)}}" method="post">
                        @csrf
                        <button class="btn btn-outline-primary">افزودن به سبد خرید</button>
                    </form>
                </div>
                <div class="bg-light-green p-3 my-4 fw-semibold">
                    {!! $book->description !!}
                </div>
                <div class="bg-light-green p-3 my-4 fw-semibold">
                    <h5 class="fw-bolder ">ISBN: </h5>
                    {{$book->ISBN}}
                </div>
                @if($book->price)
                    <div class="bg-light-green p-3 my-4 fw-semibold">
                        <h5 class="fw-bolder "> قیمت</h5>
                        {{$book->price}}
                    </div>
                @endif
                <div class="bg-light-green p-3 my-4 fw-semibold">
                    <h5 class="fw-bolder "> نویسنده</h5>
                    {{$book->authors->first()?->name}}
                </div>
                @if($book->publisher->name)
                    <div class="bg-light-green p-3 my-4 fw-semibold">
                        <h5 class="fw-bolder "> انتشارات</h5>
                        {{$book->publisher->name}}
                    </div>
                @endif
                @if($book->attributes)
                    <h5 class="fw-bolder "> ویژگی</h5>
                    @foreach($book->attributes as $attribute)
                        <div class="bg-light-green p-3 my-4 fw-semibold">
                            <strong>{{ $attribute->name }}:</strong>
                            {{ $attribute->values->firstWhere('id', $attribute->pivot->value_id) ?->value ?? ''}}
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

    </div>
@endsection
