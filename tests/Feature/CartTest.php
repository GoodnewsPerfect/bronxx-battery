<?php

use App\Models\CartItem;
use App\Models\Order;
use App\Models\User;

it('adds a guest cart item to the database and session', function () {
    $this->from('/product')
        ->post(route('cart.add'), [
            'product_id' => 1,
            'quantity' => 2,
        ])
        ->assertRedirect('/product')
        ->assertSessionHas('success')
        ->assertSessionHas('cart.items.1.quantity', 2);

    $this->assertDatabaseHas('cart_items', [
        'user_id' => null,
        'product_id' => 1,
        'product_name' => 'Bronx Batteries',
        'quantity' => 2,
        'subtotal' => 3.00,
    ]);
});

it('increments an existing cart item instead of creating a duplicate', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('cart.add'), [
        'product_id' => 1,
        'quantity' => 2,
    ]);

    $this->actingAs($user)->post(route('cart.add'), [
        'product_id' => 1,
        'quantity' => 3,
    ])->assertSessionHas('cart.items.1.quantity', 5);

    expect(CartItem::where('user_id', $user->id)->where('product_id', 1)->count())->toBe(1);

    $this->assertDatabaseHas('cart_items', [
        'user_id' => $user->id,
        'product_id' => 1,
        'quantity' => 5,
        'subtotal' => 7.50,
    ]);
});

it('stores authenticated cart items against the user', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('cart.add'), [
            'product_id' => 1,
            'quantity' => 1,
        ])
        ->assertSessionHas('cart.items.1.quantity', 1);

    $this->assertDatabaseHas('cart_items', [
        'user_id' => $user->id,
        'product_id' => 1,
        'product_name' => 'Bronx Batteries',
        'quantity' => 1,
    ]);
});

it('keeps guest cart items visible after the customer logs in', function () {
    $user = User::factory()->create();

    $this->post(route('cart.add'), [
        'product_id' => 1,
        'quantity' => 2,
    ]);

    $this->actingAs($user)
        ->get(route('product.index'))
        ->assertOk();

    $this->assertDatabaseHas('cart_items', [
        'user_id' => $user->id,
        'product_id' => 1,
        'quantity' => 2,
    ]);

    $this->assertDatabaseMissing('cart_items', [
        'user_id' => null,
        'product_id' => 1,
    ]);
});

it('shows checkout without creating an order', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('cart.add'), [
        'product_id' => 1,
        'quantity' => 2,
    ]);

    $this->actingAs($user)
        ->get(route('checkout'))
        ->assertOk();

    expect(Order::count())->toBe(0);
});

it('creates an order when checkout is completed', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('cart.add'), [
        'product_id' => 1,
        'quantity' => 2,
    ]);

    $this->actingAs($user)
        ->post(route('checkout.store'), [
            'payment_method' => 'espees',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('orders', [
        'user_id' => $user->id,
        'total_amount' => 3.00,
        'payment_method' => 'espees',
        'shipping_address' => null,
    ]);

    $this->assertDatabaseHas('order_items', [
        'product_name' => 'Bronx Batteries',
        'quantity' => 2,
        'subtotal' => 3.00,
    ]);

    expect(CartItem::where('user_id', $user->id)->count())->toBe(0);
});
