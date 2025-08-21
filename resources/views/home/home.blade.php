@extends('home.layout')

@section('content')

    <section class="flex justify-center py-10">
        <div class="swiper w-full max-w-7xl rounded-xl overflow-hidden shadow-lg">
            <div class="swiper-wrapper">
                {{-- Dinamik Bannerlar --}}
                @foreach($banners as $banner)
                    <div class="swiper-slide">
                        <a href="{{ $banner->link ?? '#' }}">
                            <img src="{{ asset('storage/' . $banner->image_path) }}" alt="Banner" class="w-full h-auto" />
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </section>

    <section class="py-2 my-2 md:py-10 md:my-10">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative bg-gray-100 p-8 rounded-xl shadow-lg">
                <div class="absolute top-1/2 left-4 md:left-8 transform -translate-y-1/2 font-extrabold text-4xl sm:text-5xl lg:text-7xl text-gray-200 opacity-60 pointer-events-none">10% OFF</div>
                <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="flex-1 text-center md:text-left">
                        <h2 class="text-3xl sm:text-4xl font-bold text-gray-800">10% İndirim Kuponu</h2>
                        <p class="mt-2 text-gray-600"> %10 İndirim Kazanmak İçin Kayıt Olun</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="{{ url('/register') }}" class="inline-block bg-gray-900 text-white font-medium py-3 px-8 rounded-md uppercase tracking-wider hover:bg-gray-800 transition-colors duration-300">Kayıt Ol</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="collections" class="py-10">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl sm:text-3xl font-bold uppercase tracking-wide">Koleksiyonlar</h2>
                <a href="#" class="inline-block text-gray-900 font-bold uppercase tracking-wider hover:text-gray-600 transition-colors duration-200">Tümünü Keşfet</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Dinamik Koleksiyonlar --}}
                @foreach($collections as $collection)
                    <div class="relative rounded-xl overflow-hidden shadow-lg group">
                        <img src="{{ asset('storage/' . $collection->image_path) }}" alt="{{ $collection->name }}" class="w-full h-96 object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex flex-col justify-end p-8">
                            <h3 class="text-white text-3xl font-bold mb-2">{{ $collection->name }}</h3>
                            <a href="{{ $collection->link }}" class="inline-block self-start border border-white text-white font-medium py-2 px-6 rounded-md uppercase tracking-wider hover:bg-white hover:text-black transition-colors duration-300">Şimdi Keşfet</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="featured-products" class="py-5">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl sm:text-3xl font-bold uppercase tracking-wide">Öne Çıkanlar</h2>
                <div class="btn-right">
                    <a href="{{route('featured_products')}}" class="inline-block text-gray-900 font-bold uppercase tracking-wider hover:text-gray-600 transition-colors duration-200">Keşfet</a>
                </div>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                @foreach($topStockProducts as $product)
                    <div class="col h-full">
                        <div class="bg-white product-card relative border rounded shadow-sm h-full flex flex-col group hover:shadow-lg transition-shadow duration-300">
                            <div class="relative w-full h-96 overflow-hidden rounded-t-lg">
                                <a href="{{ route('product_detail', $product->id) }}">
                                    <img src="{{ $product->featuredImage ? asset($product->featuredImage->image_path) : asset('images/default.jpg') }}"
                                         alt="{{ $product->name }}"
                                         class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                         style="object-position: center top;">
                                </a>
                            </div>
                            <div class="p-4 flex-grow flex flex-col justify-between">
                                <span class="text-sm font-semibold text-gray-800">{{ $product->name }}</span>
                                <span class="text-sm text-gray-600 font-medium mt-2">@if($product->discountedPrice < $product->price)
                                        <span class="text-gray-600 font-medium line-through mr-2">{{ number_format($product->price, 2) }}₺</span>
                                        <span class="text-red-600 font-bold">{{ number_format($product->discountedPrice, 2) }}₺</span>
                                    @else
                                        <span class="text-gray-600 font-medium">{{ number_format($product->price, 2) }}₺</span>
                                    @endif</span>
                                <a href="{{ route('product_detail', $product->id) }}" class="mt-4 w-full text-center border border-gray-900 text-gray-900 font-medium py-2 rounded-md uppercase tracking-wider hover:bg-gray-900 hover:text-white transition-colors duration-300">İncele</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="categories" class="py-5">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl sm:text-3xl font-bold uppercase tracking-wide">Kategoriler</h2>
            </div>
            <div class="relative">
                <button id="prevBtn" class="slider-btn absolute -left-2 top-1/2 -translate-y-1/2 z-10 bg-white bg-opacity-80 rounded-full w-10 h-10 flex items-center justify-center text-3xl text-gray-700 hover:bg-opacity-100 transition-colors duration-200 shadow-md">‹</button>
                <div class="category-slider-wrapper overflow-hidden">
                    <div class="category-slider flex transition-transform duration-500 ease-in-out">
                        @foreach($categories as $category)
                            <div class="category-card flex-shrink-0 w-64 h-96 mr-4 last:mr-0">
                                <a href="{{ route('category_products', ['slug' => $category->slug]) }}" class="block w-full h-full group">
                                    <div class="relative w-full h-full rounded-lg overflow-hidden shadow-md group-hover:shadow-xl transition-shadow duration-300">
                                        <img src="{{ asset('storage/' . $category->image_path) }}" alt="{{ $category->category_name }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent"></div>
                                        <div class="absolute bottom-0 left-0 p-4 w-full">
                                            <h4 class="text-white text-xl font-bold">{{ $category->category_name }}</h4>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                <button id="nextBtn" class="slider-btn absolute -right-2 top-1/2 -translate-y-1/2 z-10 bg-white bg-opacity-80 rounded-full w-10 h-10 flex items-center justify-center text-3xl text-gray-700 hover:bg-opacity-100 transition-colors duration-200 shadow-md">›</button>
            </div>
        </div>
    </section>

    <section id="latest-products" class="py-5">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl sm:text-3xl font-bold uppercase tracking-wide">Yeni Gelenler</h2>
                <div class="btn-right">
                    <a href="{{route('new_products')}}" class="inline-block text-gray-900 font-bold uppercase tracking-wider hover:text-gray-600 transition-colors duration-200">Keşfet</a>
                </div>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                @foreach($latestProducts as $product)
                    <div class="col h-full">
                        <div class="bg-white product-card relative border rounded shadow-sm h-full flex flex-col group hover:shadow-lg transition-shadow duration-300">
                            <div class="relative w-full h-96 overflow-hidden rounded-t-lg">
                                <a href="{{ route('product_detail', $product->id) }}">
                                    <img src="{{ $product->featuredImage ? asset($product->featuredImage->image_path) : asset('images/default.jpg') }}"
                                         alt="{{ $product->name }}"
                                         class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                         style="object-position: center top;">
                                </a>
                            </div>
                            <div class="p-4 flex-grow flex flex-col justify-between">
                                <span class="text-sm font-semibold text-gray-800">{{ $product->name }}</span>
                                <span class="text-sm text-gray-600 font-medium mt-2">@if($product->discountedPrice < $product->price)
                                        <span class="text-gray-600 font-medium line-through mr-2">{{ number_format($product->price, 2) }}₺</span>
                                        <span class="text-red-600 font-bold">{{ number_format($product->discountedPrice, 2) }}₺</span>
                                    @else
                                        <span class="text-gray-600 font-medium">{{ number_format($product->price, 2) }}₺</span>
                                    @endif</span>
                                <a href="{{ route('product_detail', $product->id) }}" class="mt-4 w-full text-center border border-gray-900 text-gray-900 font-medium py-2 rounded-md uppercase tracking-wider hover:bg-gray-900 hover:text-white transition-colors duration-300">İncele</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="instagram-gallery" class="py-10 bg-gray-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8">
                <h2 class="text-2xl sm:text-3xl font-bold uppercase tracking-wide">Instagram'dan Alışveriş Yap</h2>
                <p class="mt-2 text-gray-600">En son kombinleri keşfedin ve kendi tarzınızı oluşturun.</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                {{-- Dinamik Instagram Gönderileri --}}
                @foreach($instagramPosts as $post)
                    <a href="{{ $post->link }}" target="_blank" class="block w-full h-64 overflow-hidden rounded-lg shadow-sm group">
                        <img src="{{ asset('storage/' . $post->image_path) }}" alt="Instagram Kombini" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                    </a>
                @endforeach
            </div>

            <div class="flex justify-center mt-8">
                <a href="https://www.instagram.com/marka_adiniz" target="_blank" class="bg-gray-900 text-white font-medium py-3 px-8 rounded-md uppercase tracking-wider hover:bg-gray-800 transition-colors duration-300 flex items-center gap-2">
                    <i class="fab fa-instagram"></i>
                    Instagram'da Bizi Takip Edin
                </a>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const slider = document.querySelector('.category-slider');
            const sliderWrapper = document.querySelector('.category-slider-wrapper');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const cards = document.querySelectorAll('.category-card');

            if (cards.length === 0) return;

            const cardWidth = cards[0].offsetWidth + 16;
            let currentTranslateX = 0;

            const updateSlider = () => {
                const visibleWidth = sliderWrapper.offsetWidth;
                const totalWidth = cards.length * cardWidth;
                const maxTranslateX = -(totalWidth - visibleWidth);

                if (currentTranslateX > 0) {
                    currentTranslateX = 0;
                }
                if (currentTranslateX < maxTranslateX) {
                    currentTranslateX = maxTranslateX;
                }

                slider.style.transform = `translateX(${currentTranslateX}px)`;
                prevBtn.disabled = currentTranslateX >= 0;
                nextBtn.disabled = currentTranslateX <= maxTranslateX;
            };

            nextBtn.addEventListener('click', () => {
                const step = sliderWrapper.offsetWidth;
                currentTranslateX -= step;
                updateSlider();
            });

            prevBtn.addEventListener('click', () => {
                const step = sliderWrapper.offsetWidth;
                currentTranslateX += step;
                updateSlider();
            });

            window.addEventListener('resize', updateSlider);
            updateSlider();
        });
    </script>
@endpush
