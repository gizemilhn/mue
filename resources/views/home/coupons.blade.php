@extends('home.layout')

@section('content')
    <div class="coupon-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="coupon-title">Kuponlarım</h1>
            <a href="{{ route('cart.index') }}" class="btn btn-primary">Sepete Git</a>
        </div>

        @if($coupons->isEmpty())
            <div class="alert alert-info">
                Şu anda aktif kuponunuz bulunmamaktadır.
            </div>
        @endif

        @foreach($coupons as $coupon)
            <div class="card mb-4 {{ $coupon->is_active ? 'border-primary' : 'border-secondary' }}">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="badge bg-{{ $coupon->is_active ? 'primary' : 'secondary' }}">
                        {{ $coupon->code }}
                    </span>
                    <span class="text-{{ $coupon->is_active ? 'success' : 'danger' }}">
                        {{ $coupon->is_active ? 'Aktif' : 'Kullanıldı' }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>İndirim Tutarı:</span>
                        <strong>
                            {{ $coupon->discount_type == 'percent'
                                ? '%'.$coupon->discount_value
                                : $coupon->discount_value.'₺' }}
                        </strong>
                    </div>

                    @if($coupon->min_amount)
                        <div class="d-flex justify-content-between mb-2">
                            <span>Minimum Sepet Tutarı:</span>
                            <strong>{{ $coupon->min_amount }}₺</strong>
                        </div>
                    @endif

                    @if($coupon->usage_limit)
                        <div class="d-flex justify-content-between mb-2">
                            <span>Kalan Kullanım:</span>
                            <strong>{{ $coupon->remaining_uses }}/{{ $coupon->usage_limit }}</strong>
                        </div>
                    @endif

                    @if($coupon->expires_at)
                        <div class="d-flex justify-content-between mb-3">
                            <span>Son Kullanma:</span>
                            <strong>{{ $coupon->expires_at->format('d.m.Y H:i') }}</strong>
                        </div>
                    @endif

                    @if($coupon->usages->isNotEmpty())
                        <div class="mt-3">
                            <h6 class="border-bottom pb-2">Kullanım Geçmişi:</h6>
                            <ul class="list-group list-group-flush">
                                @foreach($coupon->usages as $usage)
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>Sipariş #{{ $usage->order_id }}</span>
                                        <span class="text-danger">-{{ $usage->discount_amount }}₺</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <style>
        .coupon-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .coupon-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: #2d3748;
        }
        .coupon-card {
            transition: all 0.3s ease;
        }
        .coupon-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection
