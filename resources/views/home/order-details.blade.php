@extends('home.layout')

@section('content')
    <div class="container mt-4">
        <h2 class="text-center mb-4">Sipariş Detayları - #{{ $order->id }}</h2>

        <div class="card mb-3">
            <div class="card-header">
                Sipariş Bilgileri
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Tarih:</strong> {{ $order->created_at->format('d-m-Y H:i') }}</p>
                        <p><strong>Durum:</strong>
                            @switch($order->status)
                                @case('pending')<span class="badge bg-warning">Beklemede</span>@break
                                @case('approved')<span class="badge bg-success">Onaylandı</span>@break
                                @case('cancelled')<span class="badge bg-danger">İptal Edildi</span>@break
                                @default<span class="badge bg-secondary">Bilinmiyor</span>
                            @endswitch
                        </p>
                    </div>
                    <div class="col-md-6">
                        @if($order->coupon)
                            <p><strong>Kupon Kodu:</strong> {{ $order->coupon->code }}</p>
                            <p><strong>İndirim:</strong>
                                {{ $order->coupon->discount_type == 'percent'
                                    ? '%'.$order->coupon->discount_value
                                    : $order->coupon->discount_value.'₺' }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                Ürünler
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>Ürün</th>
                            <th class="text-center">Adet</th>
                            <th class="text-end">Birim Fiyat</th>
                            <th class="text-end">Toplam</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($order->products as $product)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($product->product->featuredImage)
                                            <img src="{{ asset($product->product->featuredImage->image_path) }}"
                                                 width="60" class="me-3" alt="{{ $product->product->name }}">
                                        @endif
                                        <div>
                                            <h6 class="mb-1">{{ $product->product->name }}</h6>
                                            @if($product->size_id)
                                                <small class="text-muted">
                                                    Beden: {{ $product->product->sizes->firstWhere('id', $product->size_id)?->name }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">{{ $product->quantity }}</td>
                                <td class="text-end">{{ number_format($product->price, 2) }}₺</td>
                                <td class="text-end">{{ number_format($product->price * $product->quantity, 2) }}₺</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot class="table-light">
                        <tr>
                            <td colspan="3" class="text-end"><strong>Ürünler Toplamı:</strong></td>
                            <td class="text-end">{{ number_format($order->products->sum(function($p) {
                                    return $p->price * $p->quantity;
                                }), 2) }}₺</td>
                        </tr>
                        @if($order->couponUsage)
                            <tr>
                                <td colspan="3" class="text-end text-danger"><strong>Kupon İndirimi:</strong></td>
                                <td class="text-end text-danger">-{{ number_format($order->couponUsage->discount_amount, 2) }}₺</td>
                            </tr>
                        @endif
                        <tr>
                            <td colspan="3" class="text-end"><strong>Kargo Ücreti:</strong></td>
                            <td class="text-end">
                                @if($order->shipping_price == 0)
                                    <span class="text-muted text-decoration-line-through">49,90₺</span>
                                    <span class="text-success">Ücretsiz</span>
                                @else
                                    {{ number_format($order->shipping_price, 2) }}₺
                                @endif
                            </td>
                        </tr>
                        <tr class="fw-bold">
                            <td colspan="3" class="text-end"><strong>Genel Toplam:</strong></td>
                            <td class="text-end">{{ number_format($order->total_price, 2) }}₺</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        @if($order->shipping)
            <div class="card mb-3">
                <div class="card-header">
                    Kargo Bilgileri
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Takip Numarası:</strong> {{ $order->shipping->tracking_number }}</p>
                            <p><strong>Kargo Durumu:</strong>
                                @switch($order->shipping->status)
                                    @case('preparing')<span class="badge bg-warning">Hazırlanıyor</span>@break
                                    @case('shipped')<span class="badge bg-primary">Kargoya Verildi</span>@break
                                    @case('delivered')<span class="badge bg-success">Teslim Edildi</span>@break
                                    @case('cancelled')<span class="badge bg-danger">İptal Edildi</span>@break
                                    @default<span class="badge bg-secondary">Belirsiz</span>
                                @endswitch
                            </p>
                        </div>
                        @if($order->shipping->status === 'delivered')
                            <div class="col-md-6">
                                <p><strong>Teslim Tarihi:</strong> {{ $order->shipping->delivered_at->format('d-m-Y H:i') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <div class="d-flex justify-content-between">
            @if($order->status == 'pending')
                <form action="{{ route('user.order.cancel', $order->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger rounded-2">Siparişi İptal Et</button>
                </form>
            @else
                <div></div>
            @endif

            @if($order->shipping && $order->shipping->status === 'delivered' &&
                now()->diffInDays($order->shipping->delivered_at) <= 14 &&
                !$order->returnRequest)
                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#returnModal">
                    İade Talebi Oluştur
                </button>
            @endif
        </div>

        <!-- Return Modal (Aynen korundu) -->
        @if($order->returnRequest)
            <div class="alert alert-info mt-3">
                <h5>İade Talep Durumu</h5>
                @if($order->returnRequest->status == 'approved')
                    <p>Talep onaylandı. İade kodunuz: <strong>{{ $order->returnRequest->return_code }}</strong></p>
                    <p>Kargo firması: {{ $order->returnRequest->cargo_company }}</p>
                @elseif($order->returnRequest->status == 'rejected')
                    <p>Talep reddedildi. Sebep: {{ $order->returnRequest->rejection_reason }}</p>
                @else
                    <p>Talep değerlendirme aşamasında</p>
                @endif
            </div>
        @endif
    </div>

    <!-- Return Modal -->
    <div class="modal fade" id="returnModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('return.request.store', $order->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">İade Talebi Oluştur</h5>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <strong>Dikkat!</strong> İade sebebinizi açıkça gösteren fotoğraflar ekleyin.
                            <ul class="mt-2">
                                <li>Defolu ürünlerde hasarı net gösterin</li>
                                <li>Ürün etiketinin göründüğünden emin olun</li>
                                <li>Birden fazla fotoğraf yükleyebilirsiniz</li>
                            </ul>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">İade Sebebi*</label>
                            <textarea name="reason" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Fotoğraflar (Maksimum 3 adet)*</label>
                            <input type="file" name="photos[]" class="form-control" multiple accept="image/*" required>
                            <small class="text-muted">JPEG veya PNG formatında, maksimum 2MB boyutunda</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Vazgeç</button>
                        <button type="submit" class="btn btn-primary">Talep Gönder</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('styles')
    <style>
        .table th {
            white-space: nowrap;
        }
        .product-image {
            max-height: 60px;
            object-fit: contain;
        }
        .card-header {
            font-weight: 600;
            background-color: #f8f9fa;
        }
    </style>
@endsection
