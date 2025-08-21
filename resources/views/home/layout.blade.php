<!DOCTYPE html>
<html>
<head>
    <title>MUE | Tarzını Yakala!</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('home.css')

    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

</head>
<body>
@include('home.header')

<main>
    @yield('content')
</main>

<footer>
    @include('home.footer')
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lightbox2@2.11.4/dist/js/lightbox.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://kit.fontawesome.com/72bbce3754.js" crossorigin="anonymous"></script>

@include('home.js')

@stack('scripts')

@if(session()->has('toastr'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toastrData = @json(session('toastr'));
            toastr[toastrData.type](toastrData.message);
        });
    </script>
@endif
</body>
</html>
