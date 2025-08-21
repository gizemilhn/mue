@extends('home.layout')

@section('content')
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl rounded-lg p-6 md:p-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                <h2 class="text-3xl font-bold text-gray-800">Sipariş Detayları - #{{ $order->id }}</h2>
                <a href="{{ route('user.orders') }}" class="mt-4 sm:mt-0 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-6 rounded-md transition-colors duration-200">
                    Siparişlerim Sayfasına Geri Dön
                </a>
            </div>

            <div class="space-y-6">
                <div class="bg-gray-100 rounded-lg shadow-sm">
                    <div class="px-6 py-4 rounded-t-lg border-b border-gray-200">
                        <h4 class="text-lg font-semibold text-gray-800">Sipariş Bilgileri</h4>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-600"><strong>Tarih:</strong> {{ $order->created_at->format('d-m-Y H:i') }}</p>
                                <p class="text-gray-600"><strong>Durum:</strong>
                                    @switch($order->status)
                                        @case('pending')<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Beklemede</span>@break
                                        @case('approved')<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Onaylandı</span>@break
                                        @case('cancelled')<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">İptal Edildi</span>@break
                                        @default<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Bilinmiyor</span>
                                    @endswitch
                                </p>
                            </div>
                            @if($order->coupon)
                                <div>
                                    <p class="text-gray-600"><strong>Kupon Kodu:</strong> {{ $order->coupon->code }}</p>
                                    <p class="text-gray-600"><strong>İndirim:</strong>
                                        {{ $order->coupon->discount_type == 'percent'
                                            ? '%'.$order->coupon->discount_value
                                            : number_format($order->coupon->discount_value, 2).'₺' }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="bg-gray-100 rounded-lg shadow-sm">
                    <div class="px-6 py-4 rounded-t-lg border-b border-gray-200">
                        <h4 class="text-lg font-semibold text-gray-800">Ürünler</h4>
                    </div>
                    <div class="p-0">
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ürün</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Adet</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Birim Fiyat</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Toplam</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                @foreach($order->products as $orderProduct)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if($orderProduct->product->featuredImage)
                                                    <img src="{{ asset($orderProduct->product->featuredImage->image_path) }}"
                                                         class="w-16 h-16 object-cover rounded mr-3" alt="{{ $orderProduct->product->name }}">
                                                @endif
                                                <div>
                                                    <h6 class="text-sm font-semibold text-gray-800">{{ $orderProduct->product->name }}</h6>
                                                    @if($orderProduct->size_id)
                                                        <small class="text-xs text-gray-500">Beden: {{ $orderProduct->product->sizes->firstWhere('id', $orderProduct->size_id)?->name }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500">{{ $orderProduct->quantity }}</td>
                                        <td class="px-6 py-4 text-right whitespace-nowrap text-sm text-gray-500">
                                            @if($orderProduct->product->price != $orderProduct->product->discountedPrice)
                                                <span class="text-xs text-gray-500 line-through mr-1">{{ number_format($orderProduct->product->price, 2) }}₺</span>
                                            @endif
                                            <span class="font-bold text-gray-800">{{ number_format($orderProduct->product->discountedPrice, 2) }}₺</span>
                                        </td>
                                        <td class="px-6 py-4 text-right whitespace-nowrap text-sm text-gray-500">
                                            {{ number_format($orderProduct->product->discountedPrice * $orderProduct->quantity, 2) }}₺
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot class="bg-gray-100 border-t border-gray-200">
                                <tr>
                                    <td colspan="3" class="px-6 py-3 text-right text-sm font-semibold text-gray-800">Ürünler Toplamı:</td>
                                    <td class="px-6 py-3 text-right text-sm font-semibold text-gray-800">
                                        {{ number_format($order->products->sum(function($orderProduct) {
                                            return $orderProduct->product->discountedPrice * $orderProduct->quantity;
                                        }), 2) }}₺
                                    </td>
                                </tr>
                                @if($order->couponUsage)
                                    <tr>
                                        <td colspan="3" class="px-6 py-3 text-right text-sm font-semibold text-red-600">Kupon İndirimi:</td>
                                        <td class="px-6 py-3 text-right text-sm font-semibold text-red-600">-{{ number_format($order->couponUsage->discount_amount, 2) }}₺</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td colspan="3" class="px-6 py-3 text-right text-sm font-semibold text-gray-800">Kargo Ücreti:</td>
                                    <td class="px-6 py-3 text-right text-sm font-semibold text-gray-800">
                                        @if($order->shipping_price == 0)
                                            <span class="text-xs text-gray-500 line-through mr-1">49,90₺</span>
                                            <span class="text-green-600">Ücretsiz</span>
                                        @else
                                            {{ number_format($order->shipping_price, 2) }}₺
                                        @endif
                                    </td>
                                </tr>
                                <tr class="font-bold">
                                    <td colspan="3" class="px-6 py-3 text-right text-lg text-gray-800">Genel Toplam:</td>
                                    <td class="px-6 py-3 text-right text-lg text-gray-800">{{ number_format($order->total_price, 2) }}₺</td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                @if($order->shipping)
                    <div class="bg-gray-100 rounded-lg shadow-sm">
                        <div class="px-6 py-4 rounded-t-lg border-b border-gray-200">
                            <h4 class="text-lg font-semibold text-gray-800">Kargo Bilgileri</h4>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-gray-600"><strong>Takip Numarası:</strong> {{ $order->shipping->tracking_number }}</p>
                                    <p class="text-gray-600"><strong>Kargo Durumu:</strong>
                                        @switch($order->shipping->status)
                                            @case('preparing')<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Hazırlanıyor</span>@break
                                            @case('shipped')<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Kargoya Verildi</span>@break
                                            @case('delivered')<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Teslim Edildi</span>@break
                                            @case('cancelled')<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">İptal Edildi</span>@break
                                            @default<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Belirsiz</span>
                                        @endswitch
                                    </p>
                                </div>
                                @if($order->shipping->status === 'delivered')
                                    <div>
                                        <p class="text-gray-600"><strong>Teslim Tarihi:</strong> {{ $order->shipping->delivered_at->format('d-m-Y H:i') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="flex flex-col sm:flex-row justify-between items-center mt-6 space-y-4 sm:space-y-0">
                @if($order->status == 'pending')
                    <form action="{{ route('user.order.cancel', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-6 rounded-md transition-colors duration-200">Siparişi İptal Et</button>
                    </form>
                @else
                    <div></div>
                @endif

                @if($order->shipping && $order->shipping->status === 'delivered' &&
                    now()->diffInDays($order->shipping->delivered_at) <= 14 &&
                    !$order->returnRequest)
                    <button type="button" class="w-full sm:w-auto bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-6 rounded-md transition-colors duration-200" id="openReturnModal">
                        İade Talebi Oluştur
                    </button>
                @endif
            </div>

            @if($order->returnRequest)
                <div class="bg-blue-100 border-l-4 border-indigo-300 text-indigo-300 p-4 rounded-md mt-6" role="alert">
                    <h5 class="font-bold">İade Talep Durumu</h5>
                    @if($order->returnRequest->status == 'approved')
                        <p class="text-sm">Talep onaylandı. İade kodunuz: <strong>{{ $order->returnRequest->return_code }}</strong></p>
                        <p class="text-sm">Kargo firması: {{ $order->returnRequest->cargo_company }}</p>
                    @elseif($order->returnRequest->status == 'rejected')
                        <p class="text-sm">Talep reddedildi. Sebep: {{ $order->returnRequest->rejection_reason }}</p>
                    @else
                        <p class="text-sm">Talep değerlendirme aşamasında</p>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <div id="returnModal" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 hidden transition-opacity duration-300">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-lg w-full relative transform transition-transform duration-300">
                <form action="{{ route('return.request.store', $order->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h5 class="text-lg font-semibold text-gray-800">İade Talebi Oluştur</h5>
                        <button type="button" class="text-gray-400 hover:text-gray-600 text-xl" id="closeModal">&times;</button>
                    </div>
                    <div class="p-6">
                        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded-md mb-4" role="alert">
                            <strong class="font-bold">Dikkat!</strong> İade sebebinizi açıkça gösteren fotoğraflar ekleyin.
                            <ul class="list-disc list-inside mt-2 text-sm">
                                <li>Defolu ürünlerde hasarı net gösterin.</li>
                                <li>Ürün etiketinin göründüğünden emin olun.</li>
                                <li>Birden fazla fotoğraf yükleyebilirsiniz.</li>
                            </ul>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">İade Sebebi*</label>
                            <textarea name="reason" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3" required></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Fotoğraflar (Maksimum 3 adet)*</label>
                            <input type="file" name="photos[]" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" multiple accept="image/*" required>
                            <small class="text-xs text-gray-500">JPEG veya PNG formatında, maksimum 2MB boyutunda</small>
                        </div>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-2">
                        <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-6 rounded-md transition-colors duration-200" id="closeModalButton">Vazgeç</button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-md transition-colors duration-200">Talep Gönder</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const modal = $('#returnModal');
            const openButton = $('#openReturnModal');
            const closeButton = $('#closeModal');
            const closeButton2 = $('#closeModalButton');

            openButton.on('click', function() {
                modal.removeClass('hidden').addClass('flex');
            });

            closeButton.on('click', function() {
                modal.removeClass('flex').addClass('hidden');
            });

            closeButton2.on('click', function() {
                modal.removeClass('flex').addClass('hidden');
            });

            $(document).on('click', function(event) {
                if ($(event.target).is(modal)) {
                    modal.removeClass('flex').addClass('hidden');
                }
            });
        });
    </script>
@endpush
