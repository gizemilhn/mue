@extends('admin.index')
@section('content')
    <div class="p-4 ">
        <div class="p-4">

            <!-- Edit Form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <!-- Header Section -->
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-800">Ana Kategori Düzenle</h1>
                        <p class="text-sm text-gray-500 mt-1">{{ $mainCategory->name }} kategorisini düzenliyorsunuz</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ URL::previous() }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            Geri Dön
                        </a>
                    </div>
                </div>

                <form action="{{route('update.main.category', $mainCategory->id)}}" method="POST" class="space-y-4">
                    @csrf

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Ana Kategori Adı</label>
                        <input type="text" name="name" value="{{$mainCategory->name}}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Ana Kategori Adı" required>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                            Güncelle
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
