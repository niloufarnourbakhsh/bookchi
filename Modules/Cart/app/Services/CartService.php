<?php

namespace Modules\Cart\Services;

use Illuminate\Container\EntryNotFoundException;
use Illuminate\Contracts\Container\CircularDependencyException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Modules\Discount\Models\Discount;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CartService
{
    public Collection $cart;

    /**
     * @throws CircularDependencyException
     * @throws NotFoundExceptionInterface
     * @throws EntryNotFoundException
     * @throws ContainerExceptionInterface
     */
    public function __construct()
    {
        $cart= collect(session()->get('cart'));
        $this->cart = $cart->count() ? $cart :collect([
            'items'=>[],
            'discount'=>null
        ]);
    }

    public function put(array $value, $object = null): static
    {
        if ($object instanceof Model) {
            $value = array_merge($value, [
                'id' => Str::random(10),
                'subject_id' => $object->id,
                'subject_type' => get_class($object),
                'discount_percent'=>0
            ]);
        } else if (! isset($value['id'])) {
            $value = array_merge($value, [
                'id' => Str::random(10),
                'discount_percent'=>0
            ]);
        }
        $this->cart['items']=collect($this->cart['items'])->put($value['id'], $value);
        session()->put('cart', $this->cart);
        return $this;
    }

    public function all(): Collection
    {
        $cart=$this->cart;
        return collect($this->cart['items'])->map(function ($item) use ($cart) {
            $item= $this->resolveCartItem($item);
            $item= $this->checkDiscountValidate($item, $cart['discount']);
            return $item;
        });
    }

    public function get($key, $withRelatedModel = true)
    {
        $item = $key instanceof Model
            ? collect($this->cart['items'])->where('subject_id', $key->id)->where('subject_type', get_class($key))->first()
            : collect($this->cart['items'])->where('id', $key)->first();
        return $withRelatedModel ? $this->resolveCartItem($item) : $item;
    }

    public function has($key): bool
    {
        if ($key instanceof Model){
             return !is_null(collect($this->cart['items'])->where('subject_id', $key->id)->where('subject_type', get_class($key))->first());
        }
        return !is_null(collect($this->cart['items'])->where('id',$key)->first());
    }

    public function update($id , mixed $options): static
    {
        $item =collect( $this->get(key: $id, withRelatedModel: false));
        if (is_numeric($options)) {
            $item=$item->merge([
                'quantity'=>$item['quantity']+ $options
            ]);
        }
        if (is_array($options)){
            $item=$item->merge($options);
        }
        $this->cart['items']= collect($this->cart['items'])->put($item['id'], $item);
        session()->put('cart', $this->cart);
        return $this;
    }

    public function delete($key): bool
    {
        if ($this->has($key)) {
            $this->cart['items'] = collect($this->cart['items'])->filter(function ($item) use ($key) {
                if ($key instanceof Model) {
                    return !(($item['subject_id'] == $key->id ) && ( $item['subject_type'] == get_class($key)));
                }
                return $item['id'] != $key;
            });
            session()->put('cart', $this->cart);
            return true;
        }
        return false;
    }
    private function resolveCartItem($item)
    {

        if (isset($item['subject_id']) && $item['subject_type']) {
            $class = $item['subject_type'];
            $subject = (new $class())->where('id', $item['subject_id'])->first();
            $item[strtolower(class_basename($item['subject_type']))] = $subject;
            unset($item['subject_type']);
            unset($item['subject_id']);
            return $item;
        }
        return $item;
    }
    public function checkDiscountValidate($items,$code)
    {
        $discount=Discount::query()->where('code', $code)->first();
        if ( $discount && $discount->expired_at > \Illuminate\Support\now()){
            if ( (!$discount->books->count() && !$discount->categories->count())
            || in_array($items['book']->id , $discount->books()->pluck('id')->toArray())
            || array_intersect($items['book']->categories->pluck('id')->toArray() , $discount->categories()->pluck('id')->toArray())

            ){
                $items['discount_percent']= $discount->percent /100;
                return  $items;
            }
        }

        return $items;
    }

    public function getDiscount()
    {

    }
    public function addDiscount($discount): static
    {
        $this->cart['discount']=$discount;
        session()->put('cart', $this->cart);
        return $this;
    }
    public function count($item)
    {
        if (!$this->has($item)) return 0;
       return  $this->get($item)['quantity'];
    }
    public function flush(): true
    {
        $this->cart=collect([
            'items'=>[],
            'discount'=>0
        ]);
        session()->forget('cart');
        return true;
    }

    public function totalPrice()
    {
        return  collect($this->cart['items'])->sum(function ($item){
            $modelName=$item['subject_type'] ?? null;
            if (! $modelName || !isset($item['subject_id'])) return 0;
            $object=$modelName::find($item['subject_id']);
            if (!$object)return 0;
           return $object->price *$item['quantity'];
     });
    }
}
