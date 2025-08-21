@extends('admin.index')
@section('content')
    <div class="p-6 min-h-screen">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="px-6 pt-6 pb-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800">Ürün Yönetimi</h2>
                        <p class="text-sm text-gray-500 mt-1">Tüm ürünlerin listesi ve yönetim paneli</p>
                    </div>
                </div>
            </div>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 p-6">
                <form action="{{ route('product_search') }}" method="GET" class="flex-1 flex gap-2">
                    <input type="search" name="search" value="{{ request()->input('search') }}"
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Ürün Ara">
                    <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        Ara
                    </button>
                </form>

                <form action="{{ route('admin.updateProductStocks') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Stokları Güncelle
                    </button>
                </form>
            </div>

            @if(session('success'))
                <div class="mx-6 mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-x-auto  rounded-lg shadow-sm ">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ürün Başlık</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ürün Detay</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fiyat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resim</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Satış</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Düzenle</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sil</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($products as $product)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{$product->name}}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">{!!Str::limit($product->description,25)!!}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{$product->category->category_name ?? 'N/A'}}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{$product->discountedPrice}}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                @if($product->sizes->count())
                                    <div class="space-y-1">
                                        @foreach($product->sizes as $size)
                                            <div>{{ $size->name }} → {{ $size->pivot->stock }}</div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-400">Stok Bilgisi Yok</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($product->featuredImage)
                                    <img src="{{ asset($product->featuredImage->image_path) }}"
                                         class="h-32 w-24 object-cover rounded-md"
                                         alt="{{ $product->name }}">
                                @else
                                    <span class="text-gray-400">No Image</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $product->is_published ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $product->is_published ? 'Satışta' : 'Satış Dışı' }}
                                    </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('update_product', ['id' => $product->id]) }}?page={{ request()->input('page', 1) }}"
                                   class="px-3 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition-colors">
                                    Düzenle
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{route('delete_product',$product->id)}}"
                                   onclick="confirmation(event)"
                                   class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors">
                                    Sil
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 pb-6 mt-4">
                {{ $products->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function confirmation(ev){
            ev.preventDefault();

            var urlToRedirect = ev.currentTarget.getAttribute('href');
            console.log(urlToRedirect);

            swal({

                title: "Are You Sure to Delete This?",
                text: "This delete will be permanent!",
                icon: "warning",
                buttons: true,
                dangerMode: true,

            })
                .then((willCancel)=>{
                    if(willCancel){
                        window.location.href=urlToRedirect;
                    }
                });
        }
    </script>
@endpush
