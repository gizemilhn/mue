@extends('home.layout')

@section('content')
    <style>
        .product-card .card-img {
            position: relative;
            width: 100%;
            height: 680px;
            overflow: hidden;
        }

        .product-card .card-img {
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

    <section style="margin-top: 60px">
        <div class="container py-4 mt-6">
            <h2 class="mb-4">{{ $category->category_name }}</h2>
            <form method="GET" class="d-flex justify-content-end mb-3">
                <select name="sort" class="form-select w-auto me-2" onchange="this.form.submit()">
                    <option value="">Varsayılan Sıralama</option>
                    <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Fiyat: Artan</option>
                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Fiyat: Azalan</option>
                </select>
            </form>

            <div class="row row-cols-1 row-cols-md-3 g-4">
                @forelse($products as $product)
                    <div class="col">
                        <div class="product-card position-relative border rounded shadow-sm h-100 d-flex flex-column">
                            <div class="card-img">
                                <img src="{{ $product->featuredImage ? asset($product->featuredImage->image_path) : asset('images/default.jpg') }}"
                                     alt="{{ $product->name }}" class="product-image img-fluid">
                                <div class="cart-concern position-absolute d-flex justify-content-center">
                                    <div class="cart-button d-flex gap-2 justify-content-center align-items-center">
                                        <a href="{{ route('product_detail', $product->id) }}" class="btn btn-light d-flex justify-content-center align-items-center p-2">
                                            <svg class="quick-view" width="20" height="20"><use xlink:href="#quick-view"></use></svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body px-2 py-2 d-flex justify-content-between align-items-center">
                                <span class="product-name fw-bold">{{ $product->name }}</span>
                                <span class="product-price text-muted">{{ number_format($product->price, 2) }}₺</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted" style=" font-size: 24px; font-weight: bold">Bu kategoride ürün bulunamadı.</p>
                @endforelse
            </div>

            <div class="paginate-links">
                {{ $products->links() }}
            </div>
        </div>
    </section>

@endsection
