<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#0056D2">

        <title inertia>{{ config('app.name', 'Bronx Batteries') }}</title>
        <meta inertia head-key="description" name="description" content="Bronx Batteries — pack of 4 alkaline batteries for 1.5 Espees. Power you can depend on.">
        <meta inertia head-key="og:title" property="og:title" content="Bronx Batteries">
        <meta inertia head-key="og:description" property="og:description" content="Pack of 4 batteries — 1.5 Espees. Power you can depend on.">
        <meta inertia head-key="og:image" property="og:image" content="{{ asset('images/product1.jpg') }}">
        <meta inertia head-key="og:type" property="og:type" content="website">

        <!-- Favicons -->
        <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
        <link rel="icon" type="image/png" href="{{ asset('images/favicon-32.png') }}" sizes="32x32">
        <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
