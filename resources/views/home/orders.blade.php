@extends('home.layout')

@section('content')
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl rounded-lg p-6 md:p-8">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Siparişlerim</h2>

            @if($orders->isEmpty())
                <div class="bg-gray-100 p-6 rounded-lg text-center">
                    <p class="text-gray-500 font-medium">Henüz bir siparişiniz bulunmamaktadır.</p>
                </div>
            @else
                <div class="hidden md:block overflow-x-auto rounded-lg shadow-md">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead class="bg-gray-100 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tarih</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Durum</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Toplam Tutar</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">İşlemler</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        @foreach($orders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('d-m-Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @switch($order->status)
                                        @case('pending')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Beklemede</span>
                                            @break
                                        @case('approved')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Onaylandı</span>
                                            @break
                                        @case('cancelled')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">İptal Edildi</span>
                                            @break
                                        @default
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Bilinmiyor</span>
                                    @endswitch
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($order->coupon)
                                        <div class="text-xs text-green-600">Kupon: {{ $order->coupon->code }}</div>
                                    @endif
                                    {{ number_format($order->total_price, 2) }}₺
                                    @if($order->couponUsage)
                                        <div class="text-xs text-red-600">-{{ number_format($order->couponUsage->discount_amount, 2) }}₺ indirim</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('user.order.details', $order->id) }}" class="text-blue-600 hover:text-blue-900 transition-colors duration-200"><i class="fas fa-eye mr-2"></i>Detaylar</a>
                                        @if($order->status == 'pending')
                                            <form action="{{ route('user.order.cancel', $order->id) }}" method="POST">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="text-red-600 hover:text-red-900 transition-colors duration-200" onclick="return confirm('Bu siparişi iptal etmek istediğinize emin misiniz?');">İptal Et</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="md:hidden space-y-4">
                    @foreach($orders as $order)
                        <div class="bg-gray-100 p-4 rounded-lg shadow-sm">
                            <div class="flex justify-between items-center border-b pb-2 mb-2">
                                <h4 class="font-bold text-gray-800">Sipariş #{{ $order->id }}</h4>
                                <span class="text-xs font-bold {{ $order->status === 'pending' ? 'text-yellow-800' : ($order->status === 'approved' ? 'text-green-800' : 'text-red-800') }}">
                                    @switch($order->status)
                                        @case('pending')Beklemede @break
                                        @case('approved')Onaylandı @break
                                        @case('cancelled')İptal Edildi @break
                                        @defaultBilinmiyor
                                    @endswitch
                                </span>
                            </div>
                            <div class="space-y-2 text-sm text-gray-600">
                                <div class="flex justify-between">
                                    <span>Tarih:</span>
                                    <span>{{ $order->created_at->format('d-m-Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Toplam Tutar:</span>
                                    <span>
                                        @if($order->coupon)
                                            <div class="text-xs text-green-600">Kupon: {{ $order->coupon->code }}</div>
                                        @endif
                                        <strong>{{ number_format($order->total_price, 2) }}₺</strong>
                                        @if($order->couponUsage)
                                            <div class="text-xs text-red-600">-{{ number_format($order->couponUsage->discount_amount, 2) }}₺ indirim</div>
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="mt-4 flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                                <a href="{{ route('user.order.details', $order->id) }}" class="w-full sm:w-auto text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md transition-colors duration-200">Detaylar</a>
                                @if($order->status == 'pending')
                                    <form action="{{ route('user.order.cancel', $order->id) }}" method="POST" class="w-full sm:w-auto">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-md transition-colors duration-200" onclick="return confirm('Bu siparişi iptal etmek istediğinize emin misiniz?');">İptal Et</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
