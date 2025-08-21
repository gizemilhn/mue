@extends('admin.index')

@section('content')
    <div class="p-6">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 pt-6 pb-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800">İndirim Yönetimi</h2>
                        <p class="text-sm text-gray-500 mt-1">Ürünlere ve kategorilere indirimler uygulayın</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <form action="{{ route('admin.discounts.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4 items-end">
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">İndirim Adı</label>
                            <input type="text" name="name" placeholder="Kara Cuma %20 İndirimi"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">İndirim Türü</label>
                            <select name="type" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="percentage">% Yüzde</option>
                                <option value="fixed">₺ Sabit</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Değer</label>
                            <input type="number" name="value" step="0.01" min="0" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ürün</label>
                            <select name="product_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Ürün Seçin (Opsiyonel)</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            <select name="category_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Kategori Seçin (Opsiyonel)</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Başlangıç Tarihi</label>
                            <input type="datetime-local" name="start_date"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bitiş Tarihi</label>
                            <input type="datetime-local" name="end_date"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="xl:col-span-1 md:col-span-2">
                            <button type="submit"
                                    class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                İndirim Ekle
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İndirim Adı</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tür / Değer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uygulandığı Yer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Geçerlilik Tarihi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($discounts as $discount)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $discount->name ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($discount->type === 'percentage')
                                        %{{ $discount->value }}
                                    @else
                                        {{ number_format($discount->value, 2) }} ₺
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($discount->product)
                                        Ürün: {{ $discount->product->name }}
                                    @elseif($discount->category)
                                        Kategori: {{ $discount->category->category_name }}
                                    @else
                                        Tüm Ürünler
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $discount->start_date ? $discount->start_date->format('d/m/Y H:i') : 'Yok' }} -
                                    {{ $discount->end_date ? $discount->end_date->format('d/m/Y H:i') : 'Yok' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <form action="{{ route('admin.discounts.destroy', $discount->id) }}" method="POST" class="inline" onsubmit="return confirm('Bu indirimi silmek istediğinize emin misiniz?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors">
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
