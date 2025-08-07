<!DOCTYPE html>
<html>
<head>
    <title>MUE | Tarzını Yakala!</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/lightbox2@2.11.4/dist/css/lightbox.min.css" rel="stylesheet" />

    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/js/app.js')
</head>
<body>

    @include('home.css')
    @include('home.header')


<main>
    @yield('content')
</main>

<footer>
    @include('home.footer')
    @include('home.js')
    @yield('scripts')
    @yield('styles')
</footer>

    <script src="https://cdn.jsdelivr.net/npm/lightbox2@2.11.4/dist/js/lightbox.min.js"></script>
</body>
</html>
