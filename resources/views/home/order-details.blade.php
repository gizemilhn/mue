@extends('home.layout')

@section('content')

    <div class="container mt-4 ">
        <h2 class="text-center mb-4">Sipariş Detayları - #{{ $order->id }}</h2>

        <div class="card mb-3">
            <div class="card-header">
                Sipariş Bilgileri
            </div>
            <div class="card-body">
                <p><strong>Tarih:</strong> {{ $order->created_at->format('d-m-Y') }}</p>
                <p><strong>Durum:</strong>
                    @switch($order->status)
                        @case('pending')
                            <span class="badge bg-warning">Beklemede</span>
                            @break
                        @case('approved')
                            <span class="badge bg-success">Onaylandı</span>
                            @break
                        @case('cancelled')
                            <span class="badge bg-danger">İptal Edildi</span>
                            @break
                        @default
                            <span class="badge bg-secondary">Bilinmiyor</span>
                    @endswitch
                </p>
                <p><strong>Toplam Tutar:</strong> {{ number_format($order->total_price, 2) }}₺</p>
            </div>
        </div>

        <h4>Ürünler</h4>
        <table class="table table-bordered border-3 rounded-2">
            <thead>
            <tr>
                <th>Ürün Adı</th>
                <th>Adet</th>
                <th>Fiyat</th>
                <th>Toplam Fiyat</th>
                <th>Kargo Bilgileri</th>
            </tr>
            </thead>
            <tbody>
            @foreach($order->products as $product)
                <tr>
                    <td>{{ $product->product->name }}</td>
                    <td>{{ $product->quantity }}</td>
                    <td>{{ number_format($product->price, 2) }}₺</td>
                    <td><p><strong>Ürün Toplamı:</strong> {{ number_format($order->total_price - $order->shipping_price, 2) }}₺</p>
                        <p><strong>Kargo Ücreti:</strong>
                            @if($order->shipping_price == 0)
                                <span class="text-muted text-decoration-line-through">49,90₺</span>
                                <span class="text-success">Ücretsiz</span>
                            @else
                                {{ number_format($order->shipping_price, 2) }}₺
                            @endif
                        </p>
                        <p><strong>Toplam Tutar:</strong> {{ number_format($order->total_price, 2) }}₺</p>
                    </td>
                    <td>
                        @if($order->shipping)

                            <p><strong>Takip Numarası:</strong> {{ $order->shipping->tracking_number }}</p>
                            <p><strong>Kargo Durumu:</strong>
                                @switch($order->shipping->status)
                                    @case('preparing')
                                        <span class="badge bg-warning">Hazırlanıyor</span>
                                        @break
                                    @case('shipped')
                                        <span class="badge bg-primary">Kargoya Verildi</span>
                                        @break
                                    @case('delivered')
                                        <span class="badge bg-success">Teslim Edildi</span>
                                        @break
                                        @case('cancelled')
                                        <span class="badge bg-danger">İptal Edildi</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">Belirsiz</span>
                                @endswitch
                            </p>
                         @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        @if($order->status == 'pending')

            <form action="{{ route('user.order.cancel', $order->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger rounded-2 mb-4 ">Siparişi İptal Et</button>
            </form>
        @endif
        @if($order->shipping && $order->shipping->status === 'delivered' && now()->diffInDays($order->shipping->delivered_at) <= 14 && !$order->returnRequest)
            <button type="button" class="btn btn-secondary rounded mb-4" data-bs-toggle="modal" data-bs-target="#returnModal">
                İade Talebi Oluştur
            </button>

            <!-- Modal -->
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
    @endif

        @if($order->returnRequest)
            <div class="alert alert-info mt-3">
                <h5>İade Talep Durumu</h5>
                @if($order->returnRequest->status == 'approved')
                    <p>Talep onaylandı. İade kodunuz: <strong>{{ $order->returnRequest->return_code }}</strong></p>
                    <p>Kargo firması: {{ $order->returnRequest->cargo_company }}</p>
                @elseif($order->returnRequest->status == 'rejected')
                    <p>Talep reddedildi. Sebep: {{ $order->returnRequest->rejection_reason }}</p>
                    <p>İtiraz için: 0850 XXX XXXX</p>
                @else
                    <p>Talep değerlendirme aşamasında</p>
                @endif
            </div>
    @endif
@endsection
