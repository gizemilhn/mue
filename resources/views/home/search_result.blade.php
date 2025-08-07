@extends('home.layout')
@section('content')
    <div class="container mt-6">
        <div class="mt-6">
            <h4 >Arama Sonuçları: "{{ $keyword }}"</h4>
            @if($products->count())
                <div class="row">
                    @foreach($products as $product)
                        <div class="col-md-3 mb-4">
                            <a href="{{ route('product_detail', $product->id) }}" class="text-decoration-none text-dark">
                                <div class="card h-100">
                                    <img id="mainImage" src="{{ asset($product->featuredImage->image_path ?? 'images/default.jpg') }}" alt="{{ $product->name }}" class="card-img-top ">
                                    <div class="card-body mt-2">
                                        <h5 class="product-name overflow-visible ">{{ $product->name }}</h5>
                                        <p class="product-price text-muted mt-4">{{ number_format($product->price, 2) }}₺</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                {{ $products->links() }}
            @else
                <p>Hiçbir sonuç bulunamadı.</p>
            @endif
        </div>

    </div>
@endsection
