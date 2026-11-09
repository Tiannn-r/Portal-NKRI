<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title inertia>{{ config('app.name', 'Portal NKRI') }}</title>

    <!-- Scripts and Styles -->
    @vite(['resources/js/app.js'])
    @inertiaHead
</head>
<body class="h-full font-sans antialiased text-content bg-white">
    @inertia
</body>
</html>
