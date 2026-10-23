<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') &middot; {{ config('app.name', 'MMSC') }}</title>

    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>

<body class="bg-slate-100 font-sans text-slate-800 antialiased">
    <div class="min-h-screen">
        @include('admin.partials.sidebar')

        <div class="lg:pl-72">
            @include('admin.partials.topbar')

            <main class="px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
                @yield('content')
            </main>

            <footer class="px-4 pb-8 text-center text-xs text-slate-400 lg:px-8">
                &copy; {{ now()->year }} {{ config('app.name', 'MMSC') }} &middot; Panel Admin
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
