<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class KingsChatService
{
    protected string $clientId;
    protected string $redirectUri;
    protected string $profileUrl;
    protected string $authUrl;
    protected string $scopes;

    public function __construct()
    {
        $this->clientId = config('services.kingschat.client_id');
        // Use the current request URL for the redirect URI if not explicitly set in config
        $this->redirectUri = config('services.kingschat.redirect_uri') ?: url('/auth/kingschat/callback');
        $this->profileUrl = config('services.kingschat.profile_url');
        $this->authUrl = config('services.kingschat.auth_url');
        // Store as raw string from config to preserve JSON format for the auth URL
        $this->scopes = config('services.kingschat.scopes') ?? '[]';
    }

    public function getAuthUrl(): string
    {
        $queryParams = http_build_query([
            'client_id' => $this->clientId,
            'scopes' => $this->scopes,
            'post_redirect' => 'true',
            'redirect_uri' => $this->redirectUri,
        ]);

        return rtrim($this->authUrl, '/') . '/?' . $queryParams;
    }

    public function fetchProfile(string $accessToken): array
    {
        $response = Http::withToken($accessToken)
            ->get($this->profileUrl);

        if ($response->failed()) {
            throw new \Exception('Failed to fetch KingsChat profile: ' . $response->body());
        }

        $data = $response->json();

        // The profile data is nested under "profile" -> "user" based on the working PHP script
        // Flatten it for easier access
        if (isset($data['profile']) && is_array($data['profile'])) {
            $data['profile']['user'] = $data['profile']['user'] ?? [];
            $data['profile']['email'] = $data['profile']['email'] ?? [];
        }

        return $data;
    }

    public function extractUserData(array $profile): array
    {
        // Handle nested structure: profile -> user
        $user = $profile['profile']['user'] ?? [];
        $email = $profile['profile']['email'] ?? [];

        return [
            'kc_user_id' => $user['user_id'] ?? null,
            'name' => ($user['name'] ?? '') ?: ($user['username'] ?? 'KingsChat User'),
            'username' => $user['username'] ?? null,
            'email' => $email['address'] ?? null,
            'phone_number' => $profile['profile']['phone_number'] ?? null,
            'profile_photo_url' => $user['avatar_url'] ?? null,
        ];
    }
}
