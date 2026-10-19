<?php

namespace Modules\Discount\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;
use Modules\Cart\Cart\Cart;
use Modules\Discount\Http\Requests\DiscountRequest;
use Modules\Discount\Models\Discount;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $discounts=Discount::query()->with(['books','categories'])->get();
        return view('discount::admin.index')->with('discounts', $discounts);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('discount::admin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DiscountRequest $request)
    {
        $discount = Discount::query()->create([
            'code' => $request->code,
            'percent' => $request->percent,
            'expired_at' => $request->expired_at
        ]);;
        if (isset($request->book_id)) $discount->books()->attach($request->book_id);
        if (isset($request->category_id)) $discount->categories()->attach($request->category_id);
        return redirect()->to('discounts');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Discount $discount)
    {
        $discount->with(['books','categories']);
        return view('discount::admin.edit')->with('discount',$discount);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DiscountRequest $request, Discount $discount)
    {
        $discount->update([
            'code'=>$request->code,
            'percent'=>$request->percent,
            'expired_at'=>$request->expired_at
        ]);
      isset($request->book_id) ? $discount->books()->sync($request->book_id): $discount->books()->detach() ;
      isset($request->category_id) ?$discount->categories()->sync($request->category_id) : $discount->categories()->detach();
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Discount $discount) {
        $discount->delete();
        return redirect()->back();
    }

    public function check(Request $request)
    {
        $validatedCode=$request->validate(['discount'=>'required']);
//        if (!Auth::check()){
//            return redirect()->back()->withErrors(['code'=>'برای اعمال کد تخفیف لطفا اول وارد اکانت خود شوید']);
//        }
        $code=Discount::query()->where('code',$request->discount)->first();
        if (!$code) return redirect()->back()->withErrors(['code'=>'کد معتبر نیست']);
        if ($code->expired_at <\Illuminate\Support\now() ) return  redirect()->back()->withErrors(['code'=>'کد تخفیف منقضی شده']);
        Cart::addDiscount($code->code);
        Cart::addDiscount($code->code);
        return  redirect()->back();
    }

    public function removeDiscount()
    {

    }
}
