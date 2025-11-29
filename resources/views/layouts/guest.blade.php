<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', App\Facades\Settings::get('meta_title', config('app.name', 'Salon Booking')))</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('description', App\Facades\Settings::get('meta_description', 'Professional salon booking and management system'))">
    <meta name="keywords" content="@yield('keywords', App\Facades\Settings::get('meta_keywords', 'salon, booking, appointment, beauty'))">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="@yield('title', App\Facades\Settings::get('meta_title', config('app.name')))">
    <meta property="og:description" content="@yield('description', App\Facades\Settings::get('meta_description'))">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    @if(App\Facades\Settings::get('header_logo'))
        <meta property="og:image" content="{{ Storage::url(App\Facades\Settings::get('header_logo')) }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @if(App\Facades\Settings::get('google_analytics'))
        <!-- Google Analytics -->
        {!! App\Facades\Settings::get('google_analytics') !!}
    @endif
    
    @stack('styles')
</head>
<body class="bg-white font-sans antialiased">
    <div class="min-h-screen flex flex-col">
        @include('components.navbar')

        <!-- Page Content -->
        <main class="flex-grow">
            @yield('content')
        </main>

        @include('components.footer')
    </div>

    @stack('scripts')
</body>
</html>
