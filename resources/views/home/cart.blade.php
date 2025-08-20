@extends('home.layout')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->has('coupon'))
        <div class="alert alert-danger">{{ $errors->first('coupon') }}</div>
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
            <form action="{{ route('cart.applyCoupon') }}" method="POST" class="mb-3">
                @csrf
                <div class="input-group">
                    <input type="text" name="coupon_code" class="form-control" placeholder="Kupon kodu" required>
                    <button type="submit" class="btn btn-success">Uygula</button>
                </div>
            </form>

            @if(session('applied_coupon'))
                <p><strong>Kupon:</strong> {{ session('applied_coupon.code') }}
                    <button class="btn btn-sm btn-outline-danger remove-coupon ms-2">
                        <i class="fas fa-times"></i> Kaldır
                    </button></p>
                <p class="text-success discount-amount"><strong>İndirim:</strong> -{{ number_format(session('applied_coupon.discount'), 2) }}₺</p>
            @endif
            @php
                $shippingCost = 49.90;
                $isFreeShipping = $totalPrice >= 750;
                $discount = session('applied_coupon.discount') ?? 0;
                $grandTotal = $totalPrice - $discount + (!$isFreeShipping ? $shippingCost : 0);
            @endphp

            <p><strong>Toplam Tutar:</strong> <span id="totalPrice">{{ number_format($totalPrice, 2) }}</span>₺</p>
            <p><strong>Toplam Adet:</strong> <span id="totalQuantity">{{ $totalQuantity }}</span></p>

            @if($isFreeShipping)
                <p><strong>Kargo:</strong> <span class="text-muted text-decoration-line-through">{{ number_format($shippingCost, 2) }}₺</span> <span class="text-success">Ücretsiz</span></p>
            @else
                <p><strong>Kargo:</strong> {{ number_format($shippingCost, 2) }}₺</p>
            @endif

            <p><strong>Genel Toplam:</strong> <span class="grand-total">{{ number_format($grandTotal, 2) }}</span>₺</p>

            <form action="{{ route('checkout.index') }}" method="GET">
                <button type="submit" class="btn btn-primary w-100 rounded-2">İleri</button>
            </form>
        </div>
    </div>
    <div class="modal fade" id="removeCouponModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kuponu Kaldır</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <i class="fas fa-exclamation-circle text-warning display-5 d-block text-center mb-3"></i>
                    <p class="text-center">Bu kuponu kaldırmak istediğinize emin misiniz?</p>
                    <p class="text-muted small text-center">"{{ session('applied_coupon.code') }}" kodu kaldırılacak</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Vazgeç</button>
                    <button type="button" class="btn btn-danger" id="confirmRemoveCoupon">Evet, Kaldır</button>
                </div>
            </div>
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
            $(document).on('click', '.remove-coupon', function() {
                $('#removeCouponModal').modal('show');
            });

// Onay butonu event'i
            $('#confirmRemoveCoupon').on('click', function() {
                $('#removeCouponModal').modal('hide');

                $.post('{{ route("cart.removeCoupon") }}', {
                    _token: '{{ csrf_token() }}'
                }).done(function(response) {
                    if(response.success) {
                        // Başarılı animasyon
                        $('.cart-summary').prepend(`
                <div class="alert alert-success alert-dismissible fade show">
                    ${response.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `);

                        // Kupon bilgilerini kaldır
                        $('.discount-amount').remove();
                        $('.remove-coupon').parent().remove();

                        // Tutarları güncelle
                        $('#totalPrice').text(response.data.totalPrice + '₺');
                        $('.grand-total').text(response.data.grandTotal + '₺');

                        // Kargo ücretini güncelle
                        updateShippingDisplay(response.data.shippingCost);

                        // 3 saniye sonra alert'i kaldır
                        setTimeout(() => {
                            $('.alert').alert('close');
                        }, 3000);
                    }
                }).fail(function(xhr) {
                    $('#removeCouponModal').modal('hide');
                    toastr.error(xhr.responseJSON?.message || 'Bir hata oluştu');
                });
            });
            function updateShippingDisplay(shippingCost) {
                if(parseFloat(shippingCost) > 0) {
                    $('strong:contains("Kargo:")').parent().html(`
            <strong>Kargo:</strong> ${shippingCost}₺
        `);
                } else {
                    $('strong:contains("Kargo:")').parent().html(`
            <strong>Kargo:</strong>
            <span class="text-muted text-decoration-line-through">49.90₺</span>
            <span class="text-success">Ücretsiz</span>
        `);
                }
            }
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

                        // Kuponlu toplamı güncelle
                        if (response.discount > 0) {
                            $('.discount-amount').text('-' + response.discount + '₺');
                        }
                        $('.grand-total').text(response.grandTotal + '₺');

                        toastr.success('Sepet güncellendi', 'Başarılı');
                    }
                }).fail(function (xhr) {
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
        #removeCouponModal .modal-content {
            border: none;
            border-radius: 12px;
            overflow: hidden;
        }

        #removeCouponModal .modal-header {
            border-bottom: none;
            background: #f8f9fa;
        }

        #removeCouponModal .modal-footer {
            border-top: none;
            padding-top: 0;
        }

        #removeCouponModal .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            padding: 8px 20px;
        }

        #removeCouponModal .fa-exclamation-circle {
            color: #ffc107;
            font-size: 3rem;
        }
        .cart-container {
            max-width: 1400px;
            margin: 40px auto;
            padding: 0 20px;
            display: flex;
            gap: 20px;
        }
        .remove-coupon {
            padding: 0.15rem 0.3rem;
            font-size: 0.75rem;
            vertical-align: middle;
        }

        .remove-coupon:hover {
            color: #fff !important;
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
