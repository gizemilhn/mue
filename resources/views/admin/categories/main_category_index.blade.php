@extends('admin.index')
@section('content')
    <div class="p-6">
        <div class="bg-white rounded-lg shadow">
            <!-- Header Section -->
            <div class="px-6 pt-6 pb-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800">Ana Kategoriler</h2>
                        <p class="text-sm text-gray-500 mt-1">Ana kategori yönetim paneli</p>
                    </div>
                </div>
            </div>

            <!-- Add Main Category Form -->
            <div class="p-6">
                <form action="{{route('add.main.category')}}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        <div class="md:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ana Kategori Adı</label>
                            <input type="text" name="name" placeholder="Ana Kategori Adı"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <button type="submit"
                                    class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                Ekle
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Main Categories Table -->
            <div class="px-6 pb-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ana Kategori Adı</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($mainCategories as $mainCategory)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{$mainCategory->name}}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                    <a href="{{route('main.category.edit',$mainCategory->id)}}"
                                       class="px-3 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition-colors">
                                        Düzenle
                                    </a>
                                    <form action="{{ route('delete.main.category', $mainCategory->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Bu ana kategoriyi silmek istediğinize emin misiniz?')"
                                                class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors">
                                            Sil
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
