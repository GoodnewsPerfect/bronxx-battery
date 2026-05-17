<?php

namespace App\Http\Middleware;

use App\Models\CartItem;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
            'cart' => fn () => $this->cart($request),
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
        ];
    }

    private function cart(Request $request): array
    {
        $this->attachSessionCartToUser($request);

        $items = $this->cartItems($request);

        if ($items === []) {
            $items = $request->session()->get('cart.items', []);
        } else {
            $request->session()->put('cart.items', $items);
        }

        return [
            'items' => $items,
            'item_count' => array_sum(array_map(fn ($item) => $item['quantity'] ?? 0, $items)),
            'total' => number_format(
                array_reduce(
                    $items,
                    fn ($sum, $item) => $sum + (($item['quantity'] ?? 0) * (float) ($item['price'] ?? 0)),
                    0
                ),
                2,
                '.',
                ''
            ),
        ];
    }

    private function cartItems(Request $request): array
    {
        $owner = $request->user()
            ? ['user_id' => $request->user()->id]
            : ['session_id' => $request->session()->getId()];

        return CartItem::query()
            ->where($owner)
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

    private function attachSessionCartToUser(Request $request): void
    {
        if (! $request->user()) {
            return;
        }

        $sessionItems = CartItem::query()
            ->whereNull('user_id')
            ->whereIn('session_id', array_unique(array_filter([
                $request->session()->getId(),
                $request->session()->get('cart.session_id'),
            ])))
            ->get();

        if ($sessionItems->isEmpty()) {
            return;
        }

        foreach ($sessionItems as $sessionItem) {
            $userItem = CartItem::query()
                ->where('user_id', $request->user()->id)
                ->where('product_id', $sessionItem->product_id)
                ->first();

            if ($userItem) {
                $userItem->quantity += $sessionItem->quantity;
                $userItem->subtotal = number_format($userItem->quantity * (float) $userItem->price, 2, '.', '');
                $userItem->save();
                $sessionItem->delete();

                continue;
            }

            $sessionItem->user_id = $request->user()->id;
            $sessionItem->session_id = $request->session()->getId();
            $sessionItem->save();
        }
    }
}
