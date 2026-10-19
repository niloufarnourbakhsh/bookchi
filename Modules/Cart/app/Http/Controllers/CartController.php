<?php

namespace Modules\Cart\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Book\Models\Book;
use Modules\Cart\Cart\Cart;

class CartController extends Controller
{

    public function addToCart(Book $book)
    {
        if (Cart::has($book)){
            if (Cart::count($book) <$book->stock){
                Cart::update($book,1);
            }

        }else{
            Cart::put([
                'quantity'=>1,
                'price'=>$book->price
            ], $book);
        }
        return redirect('cart');
    }

    public function removeFromCart(Book $book)
    {
         Cart::delete($book);
        return redirect('cart');
    }
    public function cart()
    {
        return view('cart::cart');
    }

    public function changeQuantity($cartId, Request $request)
    {
        $request->validate(['quantity', $request->quantity]);
        if (Cart::has($cartId)) {
            Cart::update($cartId, ['quantity'=>$request->quantity]);
        }
        return redirect('cart');
    }

    public function flushCart()
    {
        Cart::flush();
        return redirect('cart');
    }
}
