@extends('admin.index')
@section('content')
    <div class="p-6">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 ">
                <h6 class="text-2xl font-semibold text-gray-800">Kullanıcı Düzenle: {{ $user->name }}</h6>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Ad</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div>
                            <label for="surname" class="block text-sm font-medium text-gray-700 mb-1">Soyad</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   id="surname" name="surname" value="{{ old('surname', $user->surname) }}" required>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Yeni Şifre (Değiştirmek istemiyorsanız boş bırakın)</label>
                            <input type="password" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   id="password" name="password">
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Şifre Tekrar</label>
                            <input type="password" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    id="role" name="role">
                                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Normal Kullanıcı</option>
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>
                        <div>
                            <label for="is_active" class="block text-sm font-medium text-gray-700 mb-1">Durum</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    id="is_active" name="is_active">
                                <option value="1" {{ $user->is_active ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ !$user->is_active ? 'selected' : '' }}>Dondurulmuş</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-3">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Güncelle
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            İptal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
