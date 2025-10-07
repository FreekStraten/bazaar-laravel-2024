<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-900">
<div class="min-h-screen flex flex-col items-center justify-center p-6">
    <div>
        <a href="{{ url('/') }}">
            <x-application-logo class="w-12 h-12 text-slate-900" />
        </a>
    </div>

    <div class="mt-6 w-full max-w-md rounded-2xl border border-slate-200 bg-white shadow-sm p-6">
        <x-flash :keys="['error','success','warning','info']" />
        {{ $slot }}
    </div>
</div>
</body>
</html>
