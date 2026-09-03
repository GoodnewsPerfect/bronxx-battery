<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscription;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $subscription = NewsletterSubscription::firstOrNew(['user_id' => $request->user()->id]);
        $alreadySubscribed = $subscription->exists;

        $subscription->email = $data['email'];
        $subscription->save();

        return back()->with('success', $alreadySubscribed
            ? "You're already subscribed to our newsletter."
            : 'Thanks for subscribing to our newsletter!');
    }
}
