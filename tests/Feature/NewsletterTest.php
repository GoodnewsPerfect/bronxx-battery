<?php

use App\Models\NewsletterSubscription;
use App\Models\User;

it('redirects guests to login instead of subscribing', function () {
    $this->post(route('newsletter.subscribe'), [
        'email' => 'guest@example.com',
    ])->assertRedirect(route('login'));

    $this->assertDatabaseCount('newsletter_subscriptions', 0);
});

it('saves the subscription and shows a success message for logged in users', function () {
    $user = User::factory()->create(['email' => 'user@example.com']);

    $this->actingAs($user)
        ->post(route('newsletter.subscribe'), [
            'email' => 'user@example.com',
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('newsletter_subscriptions', [
        'user_id' => $user->id,
        'email' => 'user@example.com',
    ]);
});

it('does not create a duplicate subscription for the same user', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('newsletter.subscribe'), ['email' => $user->email]);
    $this->actingAs($user)->post(route('newsletter.subscribe'), ['email' => $user->email]);

    expect(NewsletterSubscription::where('user_id', $user->id)->count())->toBe(1);
});
