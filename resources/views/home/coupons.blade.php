@extends('home.layout')

@section('content')
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl rounded-lg p-6 md:p-8">
            <div class="flex flex-col sm:flex-row justify-center items-center">
                <h1 class="text-3xl font-bold text-gray-800 mb-2 sm:mb-0">Kuponlarım</h1>
            </div>
            <div class="flex justify-end my-4">
                <a href="{{ route('cart.index') }}" class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md transition-colors duration-200">Sepete Git</a>
            </div>
            @if($coupons->isEmpty())
                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded-md" role="alert">
                    <p class="font-medium">Şu anda aktif kuponunuz bulunmamaktadır.</p>
                </div>
            @endif

            <div class="grid grid-cols-1 gap-6 mt-6">
                @foreach($coupons as $coupon)
                    <div class="coupon-card bg-white rounded-lg shadow-md hover:shadow-xl transform transition-transform duration-300 {{ $coupon->is_active ? 'border-l-4 border-blue-500' : 'border-l-4 border-gray-400' }}">
                        <div class="p-6">
                            <div class="flex justify-between items-center pb-4 border-b border-gray-200 mb-4">
                                <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full cursor-pointer copy-coupon"
                                      data-coupon-code="{{ $coupon->code }}"
                                      title="Kopyalamak için tıklayın">
                                    {{ $coupon->code }}
                                </span>
                                <span class="text-sm font-bold {{ $coupon->is_active ? 'text-green-600' : 'text-red-500' }}">
                                    {{ $coupon->is_active ? 'Aktif' : 'Kullanıldı' }}
                                </span>
                            </div>

                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">İndirim Tutarı:</span>
                                    <strong class="text-gray-800">
                                        {{ $coupon->discount_type == 'percent'
                                            ? '%'.$coupon->discount_value
                                            : number_format($coupon->discount_value, 2).'₺' }}
                                    </strong>
                                </div>

                                @if($coupon->min_amount)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Minimum Sepet Tutarı:</span>
                                        <strong class="text-gray-800">{{ number_format($coupon->min_amount, 2) }}₺</strong>
                                    </div>
                                @endif

                                @if($coupon->usage_limit)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Kalan Kullanım:</span>
                                        <strong class="text-gray-800">{{ $coupon->remaining_uses }}/{{ $coupon->usage_limit }}</strong>
                                    </div>
                                @endif

                                @if($coupon->expires_at)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Son Kullanma:</span>
                                        <strong class="text-gray-800">{{ $coupon->expires_at->format('d.m.Y H:i') }}</strong>
                                    </div>
                                @endif
                            </div>

                            @if($coupon->usages->isNotEmpty())
                                <div class="mt-4 border-t border-gray-200 pt-4">
                                    <h6 class="text-base font-semibold text-gray-700 mb-2">Kullanım Geçmişi:</h6>
                                    <ul class="space-y-2">
                                        @foreach($coupon->usages as $usage)
                                            <li class="flex justify-between items-center text-sm">
                                                <span class="text-gray-600">Sipariş #{{ $usage->order_id }}</span>
                                                <span class="text-red-500 font-semibold">-{{ number_format($usage->discount_amount, 2) }}₺</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .coupon-card:hover {
            transform: translateY(-3px);
        }
    </style>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "3000"
            };

            $(document).on('click', '.copy-coupon', function() {
                const couponCode = $(this).data('coupon-code');
                navigator.clipboard.writeText(couponCode).then(() => {
                    toastr.success(`'${couponCode}' kodu panoya kopyalandı.`);
                }).catch(err => {
                    toastr.error('Kopyalama başarısız oldu, lütfen manuel kopyalayın.');
                });
            });
        });
    </script>
@endpush
