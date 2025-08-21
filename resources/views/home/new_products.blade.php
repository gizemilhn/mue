@extends('home.layout')

@section('content')
    <style>
        .product-card .card-img {
            position: relative;
            width: 100%;
            height: 400px; /* Ürün kartı görsel yüksekliği */
            overflow: hidden;
        }

        .product-card .card-img img {
            object-fit: cover;
            width: 100%;
            height: 100%;
        }

        .cart-concern {
            position: absolute;
            bottom: 50%;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            transition: opacity 0.3s ease;
            visibility: hidden;
        }

        .product-card:hover .cart-concern {
            opacity: 1;
            visibility: visible;
        }

        .paginate-links {
            display: flex;
            justify-content: center;
            margin-top: 36px;
        }
    </style>

    <section class="mt-[60px]">
        <div class="container mx-auto max-w-7xl py-4 mt-6 px-4 sm:px-6 lg:px-8">
            <h2 class="mb-4 text-2xl font-bold">Yeni Gelenler</h2>
            <form method="GET" class="flex justify-end mb-3">
                <select name="sort" class="form-select w-auto mr-2 border border-gray-300 rounded-md" onchange="this.form.submit()">
                    <option value="">Varsayılan Sıralama</option>
                    <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Fiyat: Artan</option>
                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Fiyat: Azalan</option>
                </select>
            </form>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($products as $product)
                    <div class="col">
                        <div class="product-card relative border rounded shadow-sm h-full flex flex-col">
                            <div class="card-img">
                                <img src="{{ $product->featuredImage ? asset($product->featuredImage->image_path) : asset('images/default.jpg') }}"
                                     alt="{{ $product->name }}" class="product-image">
                                <div class="cart-concern absolute flex justify-center">
                                    <div class="cart-button flex gap-2 justify-center items-center">
                                        <a href="{{ route('product_detail', $product->id) }}" class="btn btn-light flex justify-center items-center p-2">
                                            <x-icon name="quick_view" class="text-gray-600" />
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6 flex justify-between items-center">
                                <span class="product-name font-bold">{{ $product->name }}</span>
                                <span class="product-price text-gray-500">{{ number_format($product->price, 2) }}₺</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-2xl font-bold">Bu kategoride ürün bulunamadı.</p>
                @endforelse
            </div>

            <div class="paginate-links flex justify-center mt-9">
                {{ $products->links() }}
            </div>
        </div>
    </section>

@endsection
