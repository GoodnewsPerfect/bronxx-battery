<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Support\ProductCatalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function add(Request $request)
    {
        Log::info('CartController@add called', [
            'user_id' => $request->user() ? $request->user()->id : 'guest',
            'input' => $request->all(),
            'session_id' => $request->session()->getId()
        ]);

        $validated = $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        Log::info('CartController@add validated input', $validated);

        $product = ProductCatalog::find($validated['product_id']);

        if (! $product) {
            Log::warning('CartController@add product not found', ['product_id' => $validated['product_id']]);
            abort(404);
        }

        if ($product['is_sold_out']) {
            return back(303)->with('error', 'This product is currently sold out.');
        }

        $productId = $product['id'];

        $cartItem = CartItem::firstOrNew([
            ...$this->cartOwner($request),
            'product_id' => $productId,
        ]);

        $cartItem->fill([
            'user_id' => $request->user()?->id,
            'session_id' => $request->session()->getId(),
            'product_name' => $product['name'],
            'product_image' => $product['image'],
            'price' => $product['price'],
            'quantity' => ($cartItem->exists ? $cartItem->quantity : 0) + $validated['quantity'],
        ]);

        $cartItem->subtotal = number_format($cartItem->quantity * (float) $cartItem->price, 2, '.', '');
        $cartItem->save();

        Log::info('CartController@add saved cart item', [
            'cart_item_id' => $cartItem->id,
            'product_id' => $productId,
            'quantity' => $cartItem->quantity,
        ]);

        $items = $this->cartItemsForRequest($request);
        $request->session()->put('cart.items', $items);
        $request->session()->put('cart.session_id', $request->session()->getId());
        $request->session()->flash('success', "{$validated['quantity']} {$product['name']}(s) added to cart.");

        Log::info('CartController@add cart after update', $request->session()->get('cart.items', []));
        Log::info('CartController@add returning back response');

        return back(303);
    }

    public function update(Request $request, int $productId)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::query()
            ->where($this->cartOwner($request))
            ->where('product_id', $productId)
            ->firstOrFail();

        $cartItem->quantity = $validated['quantity'];
        $cartItem->subtotal = number_format($cartItem->quantity * (float) $cartItem->price, 2, '.', '');
        $cartItem->save();

        $request->session()->put('cart.items', $this->cartItemsForRequest($request));
        $request->session()->put('cart.session_id', $request->session()->getId());

        return back(303);
    }

    public function destroy(Request $request, int $productId)
    {
        CartItem::query()
            ->where($this->cartOwner($request))
            ->where('product_id', $productId)
            ->delete();

        $request->session()->put('cart.items', $this->cartItemsForRequest($request));
        $request->session()->put('cart.session_id', $request->session()->getId());

        return back(303);
    }

    public function clear(Request $request)
    {
        CartItem::query()
            ->where($this->cartOwner($request))
            ->delete();

        $request->session()->forget('cart.items');
        $request->session()->forget('cart.session_id');

        return back(303);
    }

    private function cartOwner(Request $request): array
    {
        if ($request->user()) {
            return ['user_id' => $request->user()->id];
        }

        return ['session_id' => $request->session()->getId()];
    }

    private function cartItemsForRequest(Request $request): array
    {
        return CartItem::query()
            ->where($this->cartOwner($request))
            ->get()
            ->mapWithKeys(fn (CartItem $item) => [
                $item->product_id => [
                    'id' => $item->product_id,
                    'name' => $item->product_name,
                    'price' => $item->price,
                    'image' => $item->product_image,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->subtotal,
                ],
            ])
            ->all();
    }

}
