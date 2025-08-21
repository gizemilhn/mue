@extends('home.layout')

@section('content')
    <div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <div class="flex-1">
                <div class="mb-4">
                    <a href="{{ asset($product->featuredImage->image_path ?? 'images/default.jpg') }}" data-lightbox="product-gallery" data-title="{{ $product->name }}">
                        <img
                            src="{{ asset($product->featuredImage->image_path ?? 'images/default.jpg') }}"
                            alt="{{ $product->name }}"
                            class="w-full rounded-lg shadow-md"
                        />
                    </a>
                </div>
                <div class="flex gap-2 overflow-x-auto pb-2">
                    @foreach($product->images as $image)
                        <a href="{{ asset($image->image_path) }}" data-lightbox="product-gallery" data-title="{{ $product->name }}">
                            <img src="{{ asset($image->image_path) }}" alt="" class="w-24 h-28 object-cover rounded-md border border-gray-200 cursor-pointer hover:border-blue-500 transition-colors duration-200" />
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $product->name }}</h1>
                <div class="text-2xl font-semibold text-rose-600 mb-6">@if($product->discountedPrice < $product->price)
                        <span class="text-gray-600 font-medium line-through mr-2">{{ number_format($product->price, 2) }}₺</span>
                        <span class="text-red-600 font-bold">{{ number_format($product->discountedPrice, 2) }}₺</span>
                    @else
                        <span class="text-gray-600 font-medium">{{ number_format($product->price, 2) }}₺</span>
                    @endif</div>

                <div class="mb-6">
                    <span class="block text-sm font-medium text-gray-700 mb-2">Beden Seçin:</span>
                    <div class="flex flex-wrap gap-2">
                        @foreach($product->sizes as $size)
                            @php $stock = $size->pivot->stock; @endphp
                            <div class="px-4 py-2 border rounded-md cursor-pointer text-sm font-medium transition-colors duration-200
                                @if($stock == 0)
                                    bg-gray-100 text-gray-400 line-through pointer-events-none
                                @else
                                    border-gray-300 hover:bg-gray-200
                                @endif"
                                 data-size-id="{{ $size->id }}"
                                 data-stock="{{ $stock }}"
                                 onclick="selectSize(this)">
                                {{ $size->name }}
                            </div>
                        @endforeach
                    </div>
                </div>

                <form action="{{ route('add_cart', $product->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" id="size_id" name="size_id" required>

                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 mb-6">
                        <div class="flex items-center border border-gray-300 rounded-md overflow-hidden h-10">
                            <button type="button" class="px-4 py-2 text-gray-600 hover:bg-gray-100" onclick="updateQuantity(-1)">−</button>
                            <input type="number" id="quantityInput" name="quantity" value="1" min="1" class="w-16 h-full text-center border-none focus:ring-0">
                            <button type="button" class="px-4 py-2 text-gray-600 hover:bg-gray-100" onclick="updateQuantity(1)">+</button>
                        </div>
                        <button class="flex-1 w-full sm:w-auto bg-gray-900 text-white font-semibold py-2 px-6 rounded-md hover:bg-gray-800 transition-colors duration-200">
                            Sepete Ekle
                        </button>
                    </div>
                </form>

                <div class="mt-8 border-t border-gray-200 pt-6">
                    <div class="border-b border-gray-200 py-4">
                        <div class="flex justify-between items-center cursor-pointer" onclick="toggleSection(this)">
                            <span class="font-semibold text-gray-800">ÜRÜN DETAYI</span>
                            <span class="text-gray-500 font-bold transform transition-transform duration-300">+</span>
                        </div>
                        <div class="mt-4 text-gray-600 text-sm leading-relaxed hidden">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>

                    <div class="border-b border-gray-200 py-4">
                        <div class="flex justify-between items-center cursor-pointer" onclick="toggleSection(this)">
                            <span class="font-semibold text-gray-800">KARGO BİLGİLERİ</span>
                            <span class="text-gray-500 font-bold transform transition-transform duration-300">+</span>
                        </div>
                        <div class="mt-4 text-gray-600 text-sm leading-relaxed hidden">
                            Tüm siparişleriniz 1-3 iş günü içerisinde kargoya verilir. 750 TL üzeri kargo ücretsizdir.
                        </div>
                    </div>

                    <div class="border-b border-gray-200 py-4">
                        <div class="flex justify-between items-center cursor-pointer" onclick="toggleSection(this)">
                            <span class="font-semibold text-gray-800">ÖDEME SEÇENEKLERİ</span>
                            <span class="text-gray-500 font-bold transform transition-transform duration-300">+</span>
                        </div>
                        <div class="mt-4 text-gray-600 text-sm leading-relaxed hidden">
                            Kredi-Banka kartı ile alışveriş yapabilirsiniz.
                        </div>
                    </div>

                    <div class="py-4">
                        <div class="flex justify-between items-center cursor-pointer" onclick="toggleSection(this)">
                            <span class="font-semibold text-gray-800">İADE & DEĞİŞİM</span>
                            <span class="text-gray-500 font-bold transform transition-transform duration-300">+</span>
                        </div>
                        <div class="mt-4 text-gray-600 text-sm leading-relaxed hidden">
                            Ürün tesliminden itibaren 14 gün içinde iade ve değişim hakkınız vardır. Etiketli, kullanılmamış ürünler geçerlidir.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            lightbox.option({
                'resizeDuration': 200,
                'wrapAround': true,
                'fadeDuration': 300,
                'imageFadeDuration': 300,
                'albumLabel': "Resim %1 / %2"
            });
            function toggleSection(header) {
                const body = header.nextElementSibling;
                const icon = header.querySelector('span:last-child');

                body.classList.toggle('hidden');
                body.classList.toggle('block');
                icon.classList.toggle('rotate-45');
            }

            function updateQuantity(change) {
                const input = document.getElementById('quantityInput');
                let current = parseInt(input.value);
                current = isNaN(current) ? 1 : current;
                current += change;
                if (current < 1) current = 1;
                input.value = current;
            }

            function selectSize(element) {
                const sizeId = element.getAttribute('data-size-id');
                const stock = parseInt(element.getAttribute('data-stock'));

                if (stock === 0) {
                    alert('Bu beden stokta yok.');
                    return;
                }

                document.getElementById('size_id').value = sizeId;

                const sizeOptions = document.querySelectorAll('[data-size-id]');
                sizeOptions.forEach(option => {
                    option.classList.remove('bg-gray-900', 'text-white');
                    option.classList.add('bg-white', 'text-gray-700', 'border-gray-300', 'hover:bg-gray-200');
                });

                element.classList.remove('bg-white', 'text-gray-700', 'border-gray-300', 'hover:bg-gray-200');
                element.classList.add('bg-gray-900', 'text-white');
            }
        </script>
    @endpush

@endsection
