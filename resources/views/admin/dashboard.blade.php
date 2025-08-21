@extends('admin.index')

@section('content')
    <div class="p-6">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Yönetici Paneli</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-700">Onay Bekleyen Siparişler</h3>
                <p class="text-4xl font-bold text-indigo-400 mt-2">{{ $pendingOrders->count() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-700">Bekleyen İade Talepleri</h3>
                <p class="text-4xl font-bold text-amber-300 mt-2">{{ $pendingReturns->count() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-700">En Çok Satan Kategori</h3>
                <p class="text-xl font-bold text-emerald-400 mt-4">{{ $topCategory ? $topCategory->category_name : 'N/A' }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-700">Toplam Müşteri</h3>
                <p class="text-4xl font-bold text-fuchsia-300 mt-2">{{ $totalCustomers }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Son Bekleyen Siparişler</h3>
                @if($pendingOrders->isEmpty())
                    <p class="text-gray-500">Bekleyen sipariş bulunmamaktadır.</p>
                @else
                    <ul class="divide-y divide-gray-200">
                        @foreach($pendingOrders as $order)
                            <li class="py-3 flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Sipariş #{{ $order->id }}</p>
                                    <p class="text-xs text-gray-500">Müşteri: {{ $order->user->name ?? 'N/A' }}</p>
                                </div>
                                <span class="text-sm font-semibold text-gray-800">{{ $order->total_price }} TL</span>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-blue-500 hover:text-blue-700 text-sm">Detaylar</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Son Bekleyen İade Talepleri</h3>
                @if($pendingReturns->isEmpty())
                    <p class="text-gray-500">Bekleyen iade talebi bulunmamaktadır.</p>
                @else
                    <ul class="divide-y divide-gray-200">
                        @foreach($pendingReturns as $return)
                            <li class="py-3 flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">İade #{{ $return->id }}</p>
                                    <p class="text-xs text-gray-500">Müşteri: {{ $return->user->name ?? 'N/A' }}</p>
                                </div>
                                <a href="{{ route('admin.returns.show', $return->id) }}" class="text-blue-500 hover:text-blue-700 text-sm">Detaylar</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Son 7 Gün Kullanıcı Kayıtları</h3>
                <canvas id="userChart"></canvas>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Son 30 Gün Toplam Satışlar</h3>
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Stoğu Az Olan Ürünler (≤ 5)</h3>
            @if($lowStockProducts->isEmpty())
                <p class="text-gray-500">Stoğu 5'ten az olan ürün bulunmamaktadır.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ürün Adı</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Beden</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mevcut Stok</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($lowStockProducts as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->product_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->size_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-red-500">{{ $item->stock }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            const userCtx = document.getElementById('userChart').getContext('2d');
            const userChart = new Chart(userCtx, {
                type: 'line',
                data: {
                    labels: @json($userDates),
                    datasets: [{
                        label: 'Yeni Kullanıcılar',
                        data: @json($userCounts),
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            const salesCtx = document.getElementById('salesChart').getContext('2d');
            const salesChart = new Chart(salesCtx, {
                type: 'bar',
                data: {
                    labels: @json($salesDates),
                    datasets: [{
                        label: 'Toplam Satış (TL)',
                        data: @json($salesTotals),
                        backgroundColor: 'rgba(16, 185, 129, 0.6)',
                        borderColor: 'rgba(16, 185, 129, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    @endpush
@endsection
