<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dark Bootstrap Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap & Icons -->
    <link rel="stylesheet" href="{{asset('/admincss/vendor/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('admincss/vendor/font-awesome/css/font-awesome.min.css')}}">

    <!-- Select2, Toastr, SweetAlert, Lightbox -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet" />

    <!-- Custom CSS -->
    @include('admin.css')
    <link rel="stylesheet" href="{{asset('/admincss/css/font.css')}}">
    <link rel="shortcut icon" href="{{asset('/admincss/img/favicon.ico')}}">

    <!-- Custom select2 styling -->
    <style>
        .select2-container .select2-selection--single {
            height: 42px !important;
            border: 1px solid #ddd !important;
            border-radius: 6px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 32px !important;
            padding-left: 12px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important;
            top: 1px;
            right: 5px;
        }
    </style>
</head>
<body class="bg-gray-50">
<div class="flex">
    @include('admin.sidebar')
    <div class="flex-1 min-h-screen">
        @include('admin.header')
        <div class="ml-64 mt-16 p-6">
            <div class="container mx-auto px-4 py-6 position-relative overflow-visible">
                @yield('content')
            </div>
        </div>
    </div>
</div>
@include('admin.js')
@stack('scripts')
</body>
</html>
