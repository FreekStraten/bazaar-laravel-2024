<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css','resources/js/app.js'])
    @stack('head')
</head>
<body class="font-sans antialiased bg-app text-primary">
<div class="min-h-screen flex flex-col">

    @include('layouts.navigation')

    @isset($header)
        <header class="bg-white/90 sticky top-0 z-30 backdrop-blur border-b border-slate-200">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endisset

    <main class="flex-1">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <footer class="border-t border-slate-200 py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-sm text-subtle">
            © {{ date('Y') }} {{ config('app.name') }}
        </div>
    </footer>
</div>

@stack('scripts')
</body>
</html>
