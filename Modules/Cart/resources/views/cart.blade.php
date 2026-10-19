@php use Modules\Cart\Cart\Cart; @endphp
@extends('layout.users-panel')
@section('content-part')
    <div class="container px-3 my-5 clearfix">
        <!-- Shopping cart table -->
        <div class="card">
            <div class="card-header">
                <h2>سبد خرید</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered m-0">
                        <thead>
                        <tr>
                            <!-- Set columns width -->
                            <th class="text-center py-3 px-4" style="min-width: 400px;">نام محصول</th>
                            <th class="text-right py-3 px-4" style="width: 150px;">قیمت واحد</th>
                            <th class="text-center py-3 px-4" style="width: 120px;">تعداد</th>
                            <th class="text-right py-3 px-4" style="width: 150px;">قیمت نهایی</th>
                            <th class="text-center align-middle py-3 px-0" style="width: 40px;">
                                <a href="#" class="shop-tooltip float-none text-light" title="" data-original-title="Clear cart"><i class="ino ion-md-trash"></i></a>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(Cart::all() as $cart)
                            @if(isset($cart['book']))
                                @php
                                    $book=$cart['book']
                                @endphp
                            @endif
                            <tr>
                                <td class="p-4">
                                    <div class="media align-items-center">
                                        <div class="media-body">
                                            <a href="#" class="d-block text-dark">{{ $book->name }} </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right font-weight-semibold align-middle p-4"> {{$book->price}}</td>
                                @if(! $cart['discount_percent'])
                                    <td class="text-right font-weight-semibold align-middle p-4"> {{$book->price}}</td>
                                @else
                                    <td class="text-right font-weight-semibold align-middle p-4">
                                        <del class="text-danger text-sm">{{$book->price}}</del>
                                        <span>
                                            {{ $book->price -( $cart['discount_percent'] * $book->price) }}
                                        </span>
                                    </td>
                                @endif

                                <td class="align-middle p-4">
                                    <form action="{{route('cart.quantity.change',  $cart['id'])}}" method="post">
                                        @csrf
                                        @method('PATCH')
                                        <select name="quantity" onchange="this.form.submit()"
                                                class="form-control text-center">
                                            @foreach(range(1, $book->stock) as $number)
                                                <option value="{{ $number }}"
                                                    {{ $cart['quantity'] == $number ? 'selected' : '' }}>
                                                    {{ $number }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                                @if( !$cart['discount_percent'])
                                    <td class="text-right font-weight-semibold align-middle p-4">{{ number_format($cart['quantity'] * $book->price) }} </td>
                                @else
                                    <td class="text-right font-weight-semibold align-middle p-4">
                                        <del class="text-muted text-sm">
                                            {{ number_format($cart['quantity'] * $book->price )}}
                                        </del>
                                        <span>
                                            {{ ($book->price -( $cart['discount_percent'] * $book->price)) * $cart['quantity']}}
                                        </span>
                                    </td>
                                @endif

                                <td class="text-center align-middle px-0">
                                    <form action="{{route('remove.book', $book)}}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn shop-tooltip close float-none text-danger" type="submit">×</button>
                                    </form>

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- / Shopping cart table -->
                <div class="d-flex flex-wrap justify-content-between align-items-center pb-4">
                    <div class="mt-4">
                        @if($discount=Cart::getDiscount())
                            <span> کد تخفیف<span class="text-success">{{$discount->code}}</span></span>
                            <div> درصد تخفیف:
                                <span> {{$discount->percent}} %</span>
                            </div>
                            <form action="{{route('discount.delete', $discount->id)}}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger">حذف کد تخفیف</button>
                            </form>
                        @else

                            <form action="{{route('discount.check')}}" method="POST">
                                @csrf
                                <input type="hidden" name="cart" value="cart">
                                <input type="text" class="form-control" name="discount" placeholder="کد تخفیف دارید">
                                <button class="btn btn-success mt-2" type="submit">اعمال کد تخفیف</button>
                                @if($errors->has('discount'))
                                    <span class="alert alert-danger mt-2">{{$errors->first('discount')}}</span>
                                @endif
                            </form>

                        @endif
                    </div>
                        <div class="d-flex">
                            <div class="text-right mt-4 mr-5">
                                <label class="text-muted font-weight-normal m-0">Discount</label>
                                <div class="text-large"><strong>$20</strong></div>
                            </div>
                        <div class="text-right mt-4">
                            <label class="text-muted font-weight-normal m-0">قیمت کل</label>
                            <div class="text-large"><strong> {{ Cart::totalPrice() }} تومان</strong></div>
                        </div>
                    </div>
                </div>

{{--                <div class="float-left">--}}
{{--                    <form action="{{route('payment')}}" method="post">--}}
{{--                        @csrf--}}
{{--                        <button type="submit" class="btn btn-lg btn-primary mt-2">پرداخت</button>--}}
{{--                    </form>--}}

{{--                </div>--}}

            </div>
        </div>
    </div>
@endsection
