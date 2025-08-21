@extends('admin.index')

@section('content')
    <div class="p-6">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 pt-6 pb-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800">Kategori Yönetimi</h2>
                        <p class="text-sm text-gray-500 mt-1">Yeni kategori ekleyebilir veya mevcut kategorileri düzenleyebilirsiniz</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <form action="{{route('add.category')}}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ana Kategori</label>
                            <select name="main_category_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Ana Kategori Seçin (Opsiyonel)</option>
                                @foreach($mainCategories as $mainCategory)
                                    <option value="{{$mainCategory->id}}">{{$mainCategory->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Adı</label>
                            <input type="text" name="category" placeholder="Kategori Adı"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Resmi</label>
                            <input type="file" name="image"
                                   class="block w-full text-sm text-gray-500
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-md file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-blue-50 file:text-blue-700
                                  hover:file:bg-blue-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Anasayfada Göster</label>
                            <select name="is_homepage_visible" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="1">Evet</option>
                                <option value="0">Hayır</option>
                            </select>
                        </div>
                        <div class="md:col-span-2 lg:col-span-1">
                            <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                Kategori Ekle
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="px-6 pb-6">
                <div class="overflow-x-auto rounded-lg shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori Adı</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resim</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Üst Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Anasayfada Görünürlük</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($categories as $category)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{$category->category_name}}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($category->image_path)
                                        <img src="{{ asset('storage/' . $category->image_path) }}"
                                             class="w-32 h-48 object-cover rounded-md border border-gray-200"
                                             alt="{{ $category->category_name }}">
                                    @else
                                        <span class="text-gray-400">Resim Yok</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $category->mainCategory->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($category->is_homepage_visible)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Görünür</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Gizli</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                    <a href="{{route('category.edit',$category->id)}}"
                                       class="px-3 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition-colors">
                                        Düzenle
                                    </a>
                                    <a href="{{route('delete.category',$category->id)}}"
                                       onclick="return confirm('Bu kategoriyi silmek istediğinize emin misiniz?')"
                                       class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors">
                                        Sil
                                    </a>
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
