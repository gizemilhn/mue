@extends('home.layout')

@section('content')
    <style>
        .product-page {
            max-width: 1200px;
            margin: 40px auto;
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
            padding: 0 16px;
        }

        .product-images {
            flex: 1 1 500px;
        }

        .main-image img {
            width: 100%;
            border: 1px solid #eee;
            border-radius: 8px;
            object-fit: contain;
        }

        .thumbnail-row {
            display: flex;
            gap: 8px;
            margin-top: 12px;
            flex-wrap: wrap;
        }

        .thumbnail-row img {
            width: 100px;
            height: 120px;
            object-fit: cover;
            border: 1px solid #ccc;
            border-radius: 6px;
            cursor: pointer;
        }

        .product-info {
            flex: 1 1 500px;
        }

        .product-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .product-price {
            font-size: 20px;
            color: #e91e63;
            margin-bottom: 20px;
        }

        .size-option {
            padding: 8px 12px;
            margin-right: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            display: inline-block;
            cursor: pointer;
            font-size: 14px;
        }
        .size-option.selected {
            background-color: #4b5563;
            color: white;
        }
        .size-option.disabled {
            color: #999;
            text-decoration: line-through;
            pointer-events: none;
            background-color: #f0f0f0;
        }

        .add-to-cart-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .toggle-section {
            margin-top: 24px;
            border-top: 1px solid #ddd;
            padding-top: 16px;
        }

        .toggle-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            font-weight: 500;
            font-size: 16px;
        }

        .toggle-body {
            display: none;
            padding-top: 12px;
            color: #555;
            font-size: 14px;
        }

        .toggle-body.active {
            display: block;
        }

        @media (max-width: 768px) {
            .product-page {
                flex-direction: column;
            }
            .add-to-cart-row {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>

    <div class="product-page">
        <div class="product-images">
            <div class="main-image">
                <a href="{{ asset($product->featuredImage->image_path ?? 'images/default.jpg') }}" data-lightbox="product-gallery" data-title="{{ $product->name }}">
                    <img
                        src="{{ asset($product->featuredImage->image_path ?? 'images/default.jpg') }}"
                        alt="{{ $product->name }}"
                        class="w-full rounded shadow-md"
                        style="max-width: 600px;"
                    />
                </a>
            </div>
            <div class="thumbnail-row">
                @foreach($product->images as $image)
                    <a href="{{ asset($image->image_path) }}" data-lightbox="product-gallery" data-title="{{ $product->name }}">
                        <img src="{{ asset($image->image_path) }}" alt="" class="w-20 h-20 object-cover rounded border" />
                    </a>
                @endforeach
            </div>
        </div>

        <div class="product-info">
            <div class="product-title">{{ $product->name }}</div>
            <div class="product-price">{{ number_format($product->price, 2) }}₺</div>

            <div class="size-options">
                @foreach($product->sizes as $size)
                    @php $stock = $size->pivot->stock; @endphp
                    <div class="size-option {{ $stock == 0 ? 'disabled' : '' }}"
                         onclick="selectSize({{ $size->id }}, {{ $stock }})">
                        {{ $size->name }}
                    </div>
                @endforeach
            </div>

            <form action="{{ route('add_cart', $product->id) }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" id="size_id" name="size_id">
                <input type="hidden" id="quantity" name="quantity" value="1">

                <div class="add-to-cart-row">
                    <div class="d-flex border rounded" style="overflow: hidden; height: 40px;">
                        <button type="button" class="btn btn-outline-secondary btn-sm px-3" onclick="updateQuantity(-1)">−</button>
                        <input type="number" id="quantityInput" name="quantity" value="1" min="1" class="form-control text-center" style="width: 60px; border: none; box-shadow: none;">
                        <button type="button" class="btn btn-outline-secondary btn-sm px-3" onclick="updateQuantity(1)">+</button>
                    </div>
                    <button class="btn btn-dark" type="submit" style="flex-grow:1;">Sepete Ekle</button>
                </div>
            </form>

            <div class="toggle-section">
                <div class="toggle-header" onclick="toggleSection(this)">
                    <span>ÜRÜN DETAYI</span>
                    <span>+</span>
                </div>
                <div class="toggle-body">
                    {!! nl2br(e($product->description)) !!}
                </div>
            </div>

            <div class="toggle-section">
                <div class="toggle-header" onclick="toggleSection(this)">
                    <span>KARGO BİLGİLERİ</span>
                    <span>+</span>
                </div>
                <div class="toggle-body">
                    Tüm siparişleriniz 1-3 iş günü içerisinde kargoya verilir. 750 TL üzeri kargo ücretsizdir.
                </div>
            </div>

            <div class="toggle-section">
                <div class="toggle-header" onclick="toggleSection(this)">
                    <span>ÖDEME SEÇENEKLERİ</span>
                    <span>+</span>
                </div>
                <div class="toggle-body">
                    Kredi kartı, havale/EFT ve kapıda ödeme seçenekleri ile alışveriş yapabilirsiniz.
                </div>
            </div>

            <div class="toggle-section">
                <div class="toggle-header" onclick="toggleSection(this)">
                    <span>İADE & DEĞİŞİM</span>
                    <span>+</span>
                </div>
                <div class="toggle-body">
                    Ürün tesliminden itibaren 14 gün içinde iade ve değişim hakkınız vardır. Etiketli, kullanılmamış ürünler geçerlidir.
                </div>
            </div>
        </div>
    </div>
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
            body.classList.toggle('active');
        }

        function updateQuantity(change) {
            const input = document.getElementById('quantityInput');
            let current = parseInt(input.value);
            current = isNaN(current) ? 1 : current;
            current += change;
            if (current < 1) current = 1;
            input.value = current;
            document.getElementById('quantity').value = current;
        }
        function selectSize(sizeId, stock) {
            if (stock === 0) {
                alert('Bu beden stokta yok.');
                return;
            }
            document.getElementById('size_id').value = sizeId;
            const sizeOptions = document.querySelectorAll('.size-option');
            sizeOptions.forEach(option => option.classList.remove('selected'));
            event.target.classList.add('selected');
        }

    </script>


@endsection
