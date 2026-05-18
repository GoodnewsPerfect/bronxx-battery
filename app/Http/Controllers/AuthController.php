<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeToBronx;
use App\Models\CartItem;
use App\Models\User;
use App\Services\GeoLocationService;
use App\Services\KingsChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Inertia\Inertia;

class AuthController extends Controller
{
    protected KingsChatService $kingsChatService;

    public function __construct(KingsChatService $kingsChatService)
    {
        $this->kingsChatService = $kingsChatService;
    }

    public function redirectToKingsChat()
    {
        return redirect()->away($this->kingsChatService->getAuthUrl());
    }

    public function handleKingsChatCallback(Request $request)
    {
        $accessToken = $request->query('accessToken') ?? $request->input('accessToken')
            ?? $request->query('access_token') ?? $request->input('access_token');

        \Log::info('KingsChat Callback', [
            'has_token' => !empty($accessToken),
            'token_length' => $accessToken ? strlen($accessToken) : 0,
            'all_query' => $request->query(),
            'all_input' => $request->input(),
            'has_fragment' => strpos($request->getRequestUri(), '#accessToken=') !== false,
            'uri' => $request->getRequestUri(),
        ]);

        if (!$accessToken) {
            // Check if it's in the fragment (some OAuth flows do this)
            // We pass the kingschat_auth_url here as well just in case
            return Inertia::render('Auth/KingsChatCallback', [
                'kingschat_auth_url' => route('auth.kingschat')
            ]);
        }

        try {
            \Log::info('Fetching KingsChat profile with token');
            $profile = $this->kingsChatService->fetchProfile($accessToken);
            \Log::info('KingsChat profile fetched', ['profile' => $profile]);

            $userData = $this->kingsChatService->extractUserData($profile);
            \Log::info('Extracted user data', ['userData' => $userData]);

            $countryCode = GeoLocationService::getCountryCode($request->ip());

            $isNewUser = !User::where('kc_user_id', $userData['kc_user_id'])->exists();

            $user = User::updateOrCreate(
                ['kc_user_id' => $userData['kc_user_id']],
                [
                    'name' => $userData['name'] ?? $userData['username'] ?? 'KingsChat User',
                    'username' => $userData['username'],
                    'email' => $userData['email'],
                    'phone_number' => $userData['phone_number'],
                    'profile_photo_url' => $userData['profile_photo_url'],
                    'access_token' => $accessToken,
                    'auth_type' => 'kingschat',
                    'country_code' => $countryCode,
                    'password' => null, // Ensure password is null for KC users
                ]
            );

            if ($isNewUser) {
                try {
                    Mail::to($user->email)->send(new WelcomeToBronx($user));
                } catch (\Exception $e) {
                    \Log::error('Failed to send welcome email: ' . $e->getMessage());
                }
            }

            Auth::login($user);
            $this->mergeGuestCartIntoUser($request, $user);

            return redirect()->intended(route('dashboard', absolute: false));
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Failed to authenticate with KingsChat: ' . $e->getMessage());
        }
    }

    public function showLogin()
    {
        return Inertia::render('Auth/Login', [
            'status' => session('status'),
            'kingschat_auth_url' => route('auth.kingschat'),
        ]);
    }

    public function regularLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $this->mergeGuestCartIntoUser($request, $request->user());

            return redirect()->intended(route('dashboard', absolute: false));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return Inertia::render('Auth/Register', [
            'kingschat_auth_url' => route('auth.kingschat'),
        ]);
    }

    public function regularRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max-255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'auth_type' => 'regular',
        ]);

        try {
            Mail::to($user->email)->send(new WelcomeToBronx($user));
        } catch (\Exception $e) {
            \Log::error('Failed to send welcome email: ' . $e->getMessage());
        }

        Auth::login($user);
        $this->mergeGuestCartIntoUser($request, $user);

        return redirect(route('dashboard', absolute: false));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logged out successfully.');
    }

    private function mergeGuestCartIntoUser(Request $request, User $user): void
    {
        $sessionIds = array_unique(array_filter([
            $request->session()->getId(),
            $request->session()->get('cart.session_id'),
        ]));

        if ($sessionIds === []) {
            return;
        }

        $guestItems = CartItem::query()
            ->whereNull('user_id')
            ->whereIn('session_id', $sessionIds)
            ->get();

        foreach ($guestItems as $guestItem) {
            $userItem = CartItem::query()
                ->where('user_id', $user->id)
                ->where('product_id', $guestItem->product_id)
                ->first();

            if ($userItem) {
                $userItem->quantity += $guestItem->quantity;
                $userItem->subtotal = number_format($userItem->quantity * (float) $userItem->price, 2, '.', '');
                $userItem->save();
                $guestItem->delete();

                continue;
            }

            $guestItem->forceFill([
                'user_id' => $user->id,
                'session_id' => $request->session()->getId(),
            ])->save();
        }

        $items = CartItem::query()
            ->where('user_id', $user->id)
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

        $request->session()->put('cart.items', $items);
        $request->session()->put('cart.session_id', $request->session()->getId());
    }
}
