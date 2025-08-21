@extends('home.layout')

@section('content')
    <div class="container mx-auto max-w-7xl py-8 px-4 sm:px-6 lg:px-8 mt-6">
        <div class="mb-8">
            <h4 class="text-2xl font-bold text-gray-800">Arama Sonuçları: "{{ $keyword }}"</h4>
        </div>

        @if($products->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <a href="{{ route('product_detail', $product->id) }}" class="group block no-underline text-gray-900">
                        <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden h-full flex flex-col">
                            <div class="relative w-full h-80 overflow-hidden">
                                <img
                                    id="mainImage"
                                    src="{{ asset($product->featuredImage->image_path ?? 'images/default.jpg') }}"
                                    alt="{{ $product->name }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                />
                            </div>
                            <div class="p-4 flex-grow flex flex-col justify-between">
                                <h5 class="text-lg font-semibold mb-2 overflow-hidden overflow-ellipsis whitespace-nowrap">{{ $product->name }}</h5>
                                <p class="text-xl font-medium text-gray-600 mt-4">@if($product->discountedPrice < $product->price)
                                        <span class="text-gray-600 font-medium line-through mr-2">{{ number_format($product->price, 2) }}₺</span>
                                        <span class="text-red-600 font-bold">{{ number_format($product->discountedPrice, 2) }}₺</span>
                                    @else
                                        <span class="text-gray-600 font-medium">{{ number_format($product->price, 2) }}₺</span>
                                    @endif</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-8 flex justify-center">
                {{ $products->links() }}
            </div>
        @else
            <div class="bg-gray-100 p-6 rounded-lg text-center">
                <p class="text-gray-500 font-medium text-lg">Hiçbir sonuç bulunamadı.</p>
            </div>
        @endif
    </div>
@endsection
