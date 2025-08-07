@extends('home.layout')
@section('content')

    <div class="container mx-auto p-4 align-self-center">
        <h2 class=" text-center text-xl font-bold mb-4">Hesap Bilgilerim</h2>

        @include('profile.partials.update-profile-information-form')
        @include('profile.partials.update-password-form')
        @include('profile.partials.delete-user-form')
    </div>
@endsection

