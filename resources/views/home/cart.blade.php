@extends('home.layout')

@section('content')
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">


        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->has('coupon'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                {{ $errors->first('coupon') }}
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-8">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-800 mb-6">Sepetiniz</h1>

                @if($carts->isEmpty())
                    <div class="text-center p-8 bg-gray-50 rounded-lg">
                        <p class="text-gray-500 font-medium">Sepetinizde ürün bulunmamaktadır.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($carts as $cart)
                            <div class="flex items-center p-4 bg-white rounded-lg shadow-sm">
                                @if ($cart->product->featuredImage)
                                    <img src="{{ asset($cart->product->featuredImage->image_path) }}" class="w-24 h-24 object-cover rounded-lg mr-4" alt="{{ $cart->product->name }}">
                                @else
                                    <div class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center mr-4">
                                        <span class="text-gray-500 text-xs">Görsel Yok</span>
                                    </div>
                                @endif

                                <div class="flex-1">
                                    <h4 class="text-lg font-semibold text-gray-800">{{ $cart->product->name }}</h4>
                                    <p class="text-sm text-gray-500">Beden: {{ $cart->size->name }}</p>
                                    <div class="flex items-center mt-2 space-x-2">
                                        <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold w-8 h-8 rounded-full transition-colors duration-200 decrease-quantity" data-cart-id="{{ $cart->id }}">-</button>
                                        <input type="number" value="{{ $cart->quantity }}" id="quantity_{{ $cart->id }}"
                                               class="w-16 text-center border border-gray-300 rounded-md py-1" min="1">
                                        <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold w-8 h-8 rounded-full transition-colors duration-200 increase-quantity" data-cart-id="{{ $cart->id }}">+</button>
                                    </div>
                                </div>

                                <strong class="text-xl font-bold text-green-600 ml-auto mr-4 md:mr-6">{{ number_format($cart->product->price, 2) }}₺</strong>

                                <button class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-md transition-colors duration-200 remove-product" data-cart-id="{{ $cart->id }}">Sil</button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="w-full md:w-full lg:w-1/3 p-6 bg-gray-100 rounded-lg shadow-lg">
                <h4 class="text-2xl font-bold text-gray-800 mb-4">Sepet Özeti</h4>

                <form action="{{ route('cart.applyCoupon') }}" method="POST" class="mb-4">
                    @csrf
                    <div class="flex space-x-2">
                        <input type="text" name="coupon_code" class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Kupon kodu" required>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-md transition-colors duration-200">Uygula</button>
                    </div>
                </form>

                @if(session('applied_coupon'))
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-600">
                            <strong>Kupon:</strong> {{ session('applied_coupon.code') }}
                            <button class="text-red-500 hover:text-red-700 ml-2 remove-coupon" title="Kuponu Kaldır">
                                <i class="fas fa-times"></i> Kaldır
                            </button>
                        </p>
                        <p class="text-sm text-green-600 font-semibold discount-amount">
                            <strong>İndirim:</strong> -{{ number_format(session('applied_coupon.discount'), 2) }}₺
                        </p>
                    </div>
                @endif

                @php
                    $shippingCost = 49.90;
                    $isFreeShipping = $totalPrice >= 750;
                    $discount = session('applied_coupon.discount') ?? 0;
                    $grandTotal = $totalPrice - $discount + (!$isFreeShipping ? $shippingCost : 0);
                @endphp

                <div class="border-t border-gray-300 pt-4 space-y-2">
                    <p class="flex justify-between items-center text-gray-700"><strong>Toplam Tutar:</strong> <span id="totalPrice">{{ number_format($totalPrice, 2) }}₺</span></p>
                    <p class="flex justify-between items-center text-gray-700"><strong>Toplam Adet:</strong> <span id="totalQuantity">{{ $totalQuantity }}</span></p>

                    @if($isFreeShipping)
                        <p class="flex justify-between items-center text-gray-700">
                            <strong>Kargo:</strong>
                            <span class="text-sm text-gray-500 line-through mr-2">{{ number_format($shippingCost, 2) }}₺</span>
                            <span class="text-green-600 font-semibold">Ücretsiz</span>
                        </p>
                    @else
                        <p class="flex justify-between items-center text-gray-700"><strong>Kargo:</strong> {{ number_format($shippingCost, 2) }}₺</p>
                    @endif

                    <p class="flex justify-between items-center text-xl font-bold text-gray-800 border-t border-gray-300 pt-4 mt-4">
                        <strong>Genel Toplam:</strong> <span class="grand-total">{{ number_format($grandTotal, 2) }}₺</span>
                    </p>
                </div>

                <form action="{{ route('checkout.index') }}" method="GET" class="mt-6">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-md transition-colors duration-200">
                        Ödeme Sayfasına İlerle
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div id="removeCouponModal" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-lg shadow-xl max-w-sm mx-auto p-6 relative">
                <button type="button" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600" data-dismiss-modal>
                    <i class="fas fa-times"></i>
                </button>
                <div class="text-center">
                    <i class="fas fa-exclamation-circle text-yellow-500 text-5xl mb-3"></i>
                    <h5 class="text-xl font-bold text-gray-800 mb-2">Kuponu Kaldır</h5>
                    <p class="text-gray-600 mb-4">Bu kuponu kaldırmak istediğinize emin misiniz?</p>
                    <p class="text-gray-400 text-xs mb-4">"{{ session('applied_coupon.code') }}" kodu kaldırılacak.</p>
                </div>
                <div class="flex justify-center space-x-4">
                    <button type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-md transition-colors duration-200" data-dismiss-modal>
                        Vazgeç
                    </button>
                    <button type="button" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-md transition-colors duration-200" id="confirmRemoveCoupon">
                        Evet, Kaldır
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

    <script>
        $(document).ready(function () {
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "3000"
            };

            $(document).on('click', '.remove-coupon', function() {
                $('#removeCouponModal').removeClass('hidden');
            });

            $(document).on('click', '[data-dismiss-modal]', function() {
                $('#removeCouponModal').addClass('hidden');
            });

            $('#confirmRemoveCoupon').on('click', function() {
                $('#removeCouponModal').addClass('hidden');

                $.post('{{ route("cart.removeCoupon") }}', {
                    _token: '{{ csrf_token() }}'
                })
                    .done(function(response) {
                        if (response.success) {
                            location.reload();
                        }
                    })
                    .fail(function(xhr) {
                        toastr.error(xhr.responseJSON?.message || 'Bir hata oluştu');
                    });
            });

            function updateCart(cartId, quantity) {
                $.post('{{ route("cart.update") }}', {
                    _token: '{{ csrf_token() }}',
                    cart: {
                        [cartId]: { quantity: quantity }
                    }
                })
                    .done(function (response) {
                        if (response.success) {
                            $('#quantity_' + cartId).val(quantity);
                            $('#totalPrice').text(response.totalPrice + '₺');
                            $('#totalQuantity').text(response.totalQuantity);

                            if (response.discount > 0) {
                                $('.discount-amount').text('-' + response.discount + '₺');
                            }
                            $('.grand-total').text(response.grandTotal + '₺');

                            updateShippingDisplay(response.shippingCost);
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

            function updateShippingDisplay(shippingCost) {
                if(shippingCost > 0) {
                    $('p:contains("Kargo:")').html(`<strong>Kargo:</strong> ${shippingCost}₺`);
                } else {
                    $('p:contains("Kargo:")').html(`<strong>Kargo:</strong> <span class="text-sm text-gray-500 line-through mr-2">49.90₺</span> <span class="text-green-600 font-semibold">Ücretsiz</span>`);
                }
            }
        });
    </script>
@endpush
