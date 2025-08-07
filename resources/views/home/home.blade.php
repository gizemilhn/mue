@extends('home.layout')

@section('content')

    <section id="hero-slider" style="display: flex; justify-content: center; padding: 40px 0;">
        <div class="swiper" style="width: 1400px; max-width: 100%; border-radius: 12px; overflow: hidden; box-shadow: 0 0 20px rgba(0,0,0,0.1);">
            <div class="swiper-wrapper">
                <!-- Slide 1 -->
                <div class="swiper-slide">
                    <img src="{{asset('images/afis1.png')}}" alt="Yeni Sezon" style="width: 100%; height: auto;" />
                </div>
                <!-- Slide 2 -->
                <div class="swiper-slide">
                    <img src="{{asset('images/afis2.png')}}" alt="Sonbahar Koleksiyon" style="width: 100%; height: auto;" />
                </div>
                <!-- Slide 3 -->
                <div class="swiper-slide">
                    <img src="{{asset('images/afis3.png')}}" alt="İndirim" style="width: 100%; height: auto;" />
                </div>
            </div>


            <div class="swiper-pagination"></div>

            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </section>
<section class="discount-coupon py-2 my-2 py-md-5 my-md-5">
    <div class="container">
        <div class="bg-gray coupon position-relative p-5">
            <div class="bold-text position-absolute">10% OFF</div>
            <div class="row justify-content-between align-items-center">
                <div class="col-lg-7 col-md-12 mb-3">
                    <div class="coupon-header">
                        <h2 class="display-7">10% İndirim Kuponu</h2>
                        <p class="m-0">%10 İndirim Kazanmak İçin Kayıt Olun</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12">
                    <div class="btn-wrap">
                        <a href="{{ url('/register') }}" class="btn btn-black btn-medium text-uppercase hvr-sweep-to-right">Kayıt Ol</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="featured-products" class="product-store">
    <div class="container-md">
        <div class="display-header d-flex align-items-center justify-content-between">
            <h2 class="section-title text-uppercase">Öne Çıkanlar</h2>
            <div class="btn-right">
                <a href="{{route('featured_products')}}" class="d-inline-block text-uppercase text-hover fw-bold">Keşfet</a>
            </div>
        </div>
        <div class="product-content padding-small">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5">
                @foreach($topStockProducts as $products)
                    <div class="col mb-4 h-100">
                        <div class="product-card position-relative border rounded shadow-sm h-100 d-flex flex-column">
                            <div class="card-img">
                                <img src="{{ $products->featuredImage ? asset($products->featuredImage->image_path) : asset('images/default.jpg') }}"
                                     alt="{{ $products->name }}"
                                     class="product-image img-fluid">
                                <div class="cart-concern position-absolute d-flex justify-content-center">
                                    <div class="cart-button d-flex gap-2 justify-content-center align-items-center">
                                        <a href="{{ route('product_detail', $products->id) }}" class="btn btn-light d-flex justify-content-center align-items-center p-2">
                                            <svg class="quick-view" width="20" height="20"><use xlink:href="#quick-view"></use></svg>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body px-2 py-2 d-flex justify-content-between align-items-center">
                                <span class="product-name fw-bold">{{ $products->name }}</span>
                                <span class="product-price text-muted">{{ number_format($products->price, 2) }}₺</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
    <section id="categories" class="py-5">
        <div class="container-md">
            <div class="display-header d-flex align-items-center justify-content-between mb-4">
                <h2 class="section-title text-uppercase">Kategoriler</h2>
            </div>

            <div class="position-relative">
                <button id="prevBtn" class="slider-btn left">‹</button>
                <div class="category-slider-wrapper overflow-hidden">
                    <div class="category-slider d-flex transition">
                        @foreach($categories as $category)
                            <div class="category-card me-3">
                                <a href="{{ route('category_products', ['slug' => $category->slug]) }}" class="text-decoration-none">
                                    <div class="card-img-wrapper position-relative rounded overflow-hidden">
                                        <img src="{{ asset('storage/' . $category->image_path) }}"
                                             alt="{{ $category->category_name }}"
                                             class="img-fluid w-100 h-100 object-fit-cover">
                                        <div class="overlay d-flex justify-content-center align-items-center">
                                            <h4 class="text-white">{{ $category->category_name }}</h4>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                <button id="nextBtn" class="slider-btn right">›</button>
            </div>
        </div>
    </section>


<section id="latest-products" class="product-store py-2 my-2 py-md-5 my-md-5 pt-0">
    <div class="container-md">
        <div class="display-header d-flex align-items-center justify-content-between">
            <h2 class="section-title text-uppercase">Yeni Gelenler</h2>
            <div class="btn-right">
                <a href="{{route('new_products')}}" class="d-inline-block text-uppercase text-hover fw-bold">Keşfet</a>
            </div>
        </div>
        <div class="product-content padding-small">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5">
                @foreach($latestProducts as $products)
                    <div class="col mb-4 h-100">
                        <div class="product-card position-relative border rounded shadow-sm h-100 d-flex flex-column">
                            <div class="card-img">
                                <img src="{{ $products->featuredImage ? asset($products->featuredImage->image_path) : asset('images/default.jpg') }}"
                                     alt="{{ $products->name }}"
                                     class="product-image img-fluid">
                                <div class="cart-concern position-absolute d-flex justify-content-center">
                                    <div class="cart-button d-flex gap-2 justify-content-center align-items-center">
                                        <a href="{{ route('product_detail', $products->id) }}" class="btn btn-light d-flex justify-content-center align-items-center p-2">
                                            <svg class="quick-view" width="20" height="20"><use xlink:href="#quick-view"></use></svg>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body px-2 py-2 d-flex justify-content-between align-items-center">
                                <span class="product-name fw-bold">{{ $products->name }}</span>
                                <span class="product-price text-muted">{{ number_format($products->price, 2) }}₺</span>
                            </div>
                        </div>
                    </div>

                @endforeach
            </div>
        </div>

    </div>
</section>

@endsection
@section('styles')
    <style>
        .category-slider-wrapper {
            width: 100%;
            overflow: hidden;
        }

        .category-slider {
            width: max-content;
            display: flex;
            transition: transform 0.5s ease;
        }

        .category-card {
            width: 250px;
            height: 400px;
            flex-shrink: 0;
            position: relative;
        }

        .card-img-wrapper {
            width: 100%;
            height: 100%;
            position: relative;
        }

        .card-img-wrapper img {
            object-fit: cover;
            width: 100%;
            height: 100%;
            display: block;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            opacity: 0;
            transition: 0.3s ease;
        }

        .card-img-wrapper:hover .overlay {
            opacity: 1;
        }

        .slider-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.8);
            border: none;
            font-size: 2rem;
            z-index: 10;
            cursor: pointer;
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .slider-btn.left {
            left: -20px;
        }

        .slider-btn.right {
            right: -20px;
        }
    </style>
@endsection
@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const slider = document.querySelector('.category-slider');
            const cards = document.querySelectorAll('.category-card');
            const cardWidth = 250 + 12; // kart genişliği + margin
            const cardsPerPage = 4;
            const totalCards = cards.length;
            const maxPage = Math.ceil(totalCards / cardsPerPage);
            let currentPage = 0;

            const updateSlider = () => {
                const translateX = -(currentPage * cardWidth * cardsPerPage);
                slider.style.transform = `translateX(${translateX}px)`;
            };

            document.getElementById('nextBtn').addEventListener('click', () => {
                if (currentPage < maxPage - 1) {
                    currentPage++;
                    updateSlider();
                }
            });

            document.getElementById('prevBtn').addEventListener('click', () => {
                if (currentPage > 0) {
                    currentPage--;
                    updateSlider();
                }
            });
        });
    </script>

@endsection
