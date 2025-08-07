@extends('admin.index')
@section('content')
    <div class="p-4">
        <div class="p-4">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    <!-- Enhanced Header Section -->
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                        <div>
                            <h1 class="text-2xl font-semibold text-gray-800">Yeni Kullanıcı Ekle</h1>
                            <p class="text-sm text-gray-500 mt-1">Yeni kullanıcı bilgilerini aşağıdaki formu doldurarak ekleyebilirsiniz</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                                </svg>
                                Geri Dön
                            </a>
                        </div>
                    </div>

                    <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6" novalidate>
                        @csrf

                        <!-- Name and Surname -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Ad <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <div class="mt-1 text-sm text-red-600 invalid-feedback">
                                    Lütfen kullanıcı adını giriniz.
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Soyad <span class="text-red-500">*</span></label>
                                <input type="text" name="surname" id="surname" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <div class="mt-1 text-sm text-red-600 invalid-feedback">
                                    Lütfen kullanıcı soyadını giriniz.
                                </div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <div class="mt-1 text-sm text-red-600 invalid-feedback">
                                Lütfen geçerli bir email adresi giriniz.
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Şifre <span class="text-red-500">*</span></label>
                                <input type="password" name="password" id="password" required minlength="8"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <p class="mt-1 text-sm text-gray-500">En az 8 karakter olmalıdır</p>
                                <div class="mt-1 text-sm text-red-600 invalid-feedback">
                                    Lütfen en az 8 karakterden oluşan bir şifre giriniz.
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Şifre Tekrar <span class="text-red-500">*</span></label>
                                <input type="password" name="password_confirmation" id="password_confirmation" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <div class="mt-1 text-sm text-red-600 invalid-feedback">
                                    Şifreler eşleşmiyor.
                                </div>
                            </div>
                        </div>

                        <!-- Role -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Rol <span class="text-red-500">*</span></label>
                            <select name="role" id="role" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="" selected disabled>Seçiniz...</option>
                                <option value="user">Normal Kullanıcı</option>
                                <option value="admin">Admin</option>
                            </select>
                            <div class="mt-1 text-sm text-red-600 invalid-feedback">
                                Lütfen bir rol seçiniz.
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4">
                            <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                Kullanıcıyı Kaydet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();

        // Password confirmation validation
        document.getElementById('password_confirmation').addEventListener('input', function() {
            var password = document.getElementById('password');
            var confirmPassword = this;

            if (password.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity('Şifreler eşleşmiyor');
            } else {
                confirmPassword.setCustomValidity('');
            }
        });

    </script>
@endpush
