@extends('admin.index')

@section('content')
    <div class="p-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-6 border-b pb-4">
                <h2 class="text-2xl font-bold text-gray-800">Sipariş Detayı #{{ $order->id }}</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:text-blue-800 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Tüm Siparişlere Dön
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">Sipariş Bilgileri</h3>
                    <div class="space-y-2 text-gray-600">
                        <p><strong>Sipariş Tarihi:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
                        <p><strong>Sipariş Durumu:</strong>
                            @switch($order->status)
                                @case('pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Beklemede</span>
                                    @break
                                @case('approved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Onaylandı</span>
                                    @break
                                @case('cancelled')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">İptal Edildi</span>
                                    @break
                            @endswitch
                        </p>
                        @if($order->status ==='approved')
                            <p><strong>Kargo Takip Kodu:</strong>{{ $order->shipping->tracking_number }} </p>
                        @endif
                        <p><strong>Toplam Tutar:</strong> {{ number_format($order->total_price, 2) }} ₺</p>
                    </div>
                </div>

                <div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">Müşteri ve Adres Bilgileri</h3>
                    <div class="space-y-2 text-gray-600">
                        <p><strong>Müşteri Adı:</strong> {{ $order->user->name }} {{ $order->user->surname }}</p>
                        <p><strong>E-posta:</strong> {{ $order->user->email }}</p>
                        <p><strong>Telefon:</strong> {{ $order->address->phone ?? 'N/A' }}</p>
                        <p><strong>Adres:</strong> {{ $order->address->address_line ?? '' }} - {{ $order->address->district ?? '' }}/ {{ $order->address->city ?? '' }}</p>
                    </div>
                </div>
            </div>

            <h3 class="text-xl font-semibold text-gray-700 mb-4">Sipariş Kalemleri</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ürün</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Beden</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Adet</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fiyat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Toplam</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($order->products as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 flex items-center">
                                @if($item->product->featuredImage)
                                    <img src="{{ asset($item->product->featuredImage->image_path) }}" alt="{{ $item->product->name }}" class="h-24 w-18 object-cover rounded mr-3">
                                @endif
                                {{ $item->product->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->size->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($item->price, 2) }} ₺</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($item->price * $item->quantity, 2) }} ₺</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            @if($order->status == 'pending')
                <div class="mt-8 flex justify-end space-x-4">
                    <form action="{{ route('admin.order.approve', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition-colors">
                            Siparişi Onayla
                        </button>
                    </form>
                    <form action="{{ route('admin.order.cancel', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition-colors">
                            Siparişi İptal Et
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
