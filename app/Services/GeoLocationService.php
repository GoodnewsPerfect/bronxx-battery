<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeoLocationService
{
    public static function getCountryCode(string $ip): ?string
    {
        try {
            // Using a free API like ip-api.com for demonstration
            $response = Http::get("http://ip-api.com/json/{$ip}");
            if ($response->successful()) {
                return $response->json('countryCode');
            }
        } catch (\Exception $e) {
            // Log error or ignore
        }
        return null;
    }
}
