@extends('home.layout')

@section('content')
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="cart-container d-flex">
        <div class="cart-items w-75">
            <h1 class="mb-4">Sepetiniz</h1>

            @if($carts->isEmpty())
                <p>Sepetinizde ürün bulunmamaktadır.</p>
            @else
                <div class="cart-items-list">
                    @foreach($carts as $cart)
                        <div class="cart-item d-flex align-items-center mb-3 p-3">
                            @if ($cart->product->featuredImage)
                                <img src="{{ asset($cart->product->featuredImage->image_path) }}" width="100" alt="{{ $cart->product->name }}">
                            @else
                                <span>No Image</span>
                            @endif

                            <div class="ms-3">
                                <div><strong>{{ $cart->product->name }}</strong></div>
                                <div>Beden: {{ $cart->size->name }}</div>

                                <div class="d-flex align-items-center mt-2">
                                    <button class="btn btn-outline-secondary decrease-quantity rounded" data-cart-id="{{ $cart->id }}">-</button>
                                    <input type="number" value="{{ $cart->quantity }}" id="quantity_{{ $cart->id }}"
                                           class="form-control mx-2 quantity-input" min="1" style="width: 60px;">
                                    <button class="btn btn-outline-secondary increase-quantity rounded" data-cart-id="{{ $cart->id }}">+</button>
                                </div>
                            </div>
                                <strong class="ms-auto fs-5 text-success">{{ number_format($cart->product->price, 2) }}₺</strong>
                            <button class="btn btn-danger ms-auto remove-product rounded-2" data-cart-id="{{ $cart->id }}">Sil</button>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="cart-summary w-25 p-3 bg-light rounded ms-3">
            <h4 class="mb-4">Sepet Özeti</h4>
            @php
                $shippingCost = 49.90;
                $isFreeShipping = $totalPrice >= 750;
            @endphp
            <p><strong>Toplam Tutar:</strong> <span id="totalPrice">{{ number_format($totalPrice ?? 0, 2) }}</span>₺</p>
            <p><strong>Toplam Adet:</strong> <span id="totalQuantity">{{ $totalQuantity ?? 0 }}</span></p>
        @if($totalPrice >= 750)
                <p><strong>Kargo:</strong> <span class="text-muted text-decoration-line-through">{{ number_format($shippingCost, 2) }}₺</span> <span class="text-success">Ücretsiz</span></p>
                <p><strong>Genel Toplam:</strong>
                    {{ isset($totalPrice) ? number_format($isFreeShipping ? $totalPrice : $totalPrice + $shippingCost, 2) : '0.00' }}₺
                </p>
            @else
                <p><strong>Kargo:</strong> {{ number_format($shippingCost, 2) }}₺</p>
                <p><strong>Genel Toplam:</strong>
                    {{ isset($totalPrice) ? number_format($isFreeShipping ? $totalPrice : $totalPrice + $shippingCost, 2) : '0.00' }}₺
                </p>
            @endif
            <form action="{{ route('checkout.index') }}" method="GET">
                <button type="submit" class="btn btn-primary w-100 rounded-2"> İleri </button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "3000"
            };

            function updateCart(cartId, quantity) {
                $.post('{{ route("cart.update") }}', {
                    _token: '{{ csrf_token() }}',
                    cart: {
                        [cartId]: { quantity: quantity }
                    }
                }).done(function (response) {
                    if (response.success) {
                        $('#quantity_' + cartId).val(quantity);
                        $('#totalPrice').text(response.totalPrice + '₺');
                        $('#totalQuantity').text(response.totalQuantity);
                        toastr.success('Sepet güncellendi', 'Başarılı');
                    }
                })
                    .fail(function (xhr) {
                        let message = 'Bir hata oluştu.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        toastr.error(message, 'Hata');
                    });
            }

            let maxStock = {!! json_encode($cartStocks) !!};

            $('.increase-quantity').on('click', function () {
                let id = $(this).data('cart-id');
                let quantity = parseInt($('#quantity_' + id).val()) + 1;
                let stock = maxStock[id];

                if (quantity <= stock) {
                    updateCart(id, quantity);
                } else {
                    toastr.warning('Bu üründen daha fazla ekleyemezsiniz. Stok limiti aşıldı.', 'Uyarı');
                }
            });

            $('.decrease-quantity').on('click', function () {
                let id = $(this).data('cart-id');
                let quantity = parseInt($('#quantity_' + id).val());
                if (quantity > 1) updateCart(id, quantity - 1);
            });

            $('.quantity-input').on('change', function () {
                let id = this.id.split('_')[1];
                let quantity = parseInt(this.value);
                if (quantity > 0) updateCart(id, quantity);
            });

            $('.remove-product').on('click', function () {
                $.post('{{ route("cart.remove") }}', {
                    _token: '{{ csrf_token() }}',
                    cart_id: $(this).data('cart-id')
                }).done(function (res) {
                    if (res.success) location.reload();
                });
            });
        });
    </script>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <style>

        .cart-container {
            max-width: 1400px;
            margin: 40px auto;
            padding: 0 20px;
            display: flex;
            gap: 20px;
        }

        .cart-items {
            flex: 1;
        }

        .cart-summary {
            flex: 0 0 350px;
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .cart-item {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.05);
        }

        .quantity-input {
            text-align: center;
        }


        @media (max-width: 768px) {
            .cart-container {
                flex-direction: column;
                align-items: center;
            }

            .cart-items {
                width: 100%;
                margin-bottom: 20px;
            }

            .cart-summary {
                width: 100%;
                margin-top: 20px;
                flex: 0;
            }

            .cart-item {
                padding: 10px;
                font-size: 14px;
            }

            .cart-item img {
                width: 80px;
            }

            .btn-primary {
                padding: 10px;
            }
        }
    </style>
@endsection
