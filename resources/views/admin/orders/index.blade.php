@extends('admin.index')
@section('content')
    <div class="p-6">
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Tüm Siparişler</h2>
                <div class="text-gray-600">Toplam: <span class="font-medium">{{ $orders->total() }}</span> kayıt</div>
            </div>

            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end mb-8">
                <div class="relative">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Sipariş no, müşteri adı..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 pl-10"
                    >
                </div>

                <!-- Durum Filtresi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 bg-white mb-1">Durum</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Tüm Durumlar</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Beklemede</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Onaylananlar</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>İptal Edilenler</option>
                    </select>
                </div>

                <!-- Tarih Filtreleri -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 bg-white mb-1">Başlangıç Tarihi</label>
                    <input type="date" name="start_date"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ request('start_date') }}">
                </div>

                <div class="flex space-x-2">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-all">
                        <i class="fas fa-filter mr-2"></i> Filtrele
                    </button>
                    @if(request()->hasAny(['search', 'status', 'start_date', 'end_date']))
                        <a href="{{ route('admin.orders.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-md transition-all">
                            <i class="fas fa-times mr-2"></i> Temizle
                        </a>
                    @endif
                </div>
            </form>


            @if($orders->isEmpty())
                <!-- Empty State -->
                <div class="bg-white rounded-lg  p-12 text-center">
                    <div class="text-gray-400 mb-4">
                        <i class="fas fa-shopping-cart text-5xl"></i>
                    </div>
                    <h4 class="text-lg font-medium text-gray-700 mb-2">Henüz sipariş bulunmamaktadır</h4>
                    <p class="text-gray-500">Müşterilerinizin siparişleri burada listelenecektir</p>
                </div>
            @else
                <div class="overflow-x-auto bg-white rounded-lg shadow-sm ">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kullanıcı</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarih</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Toplam Tutar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($orders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $order->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->user->name }} {{ $order->user->surname }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('d-m-Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
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
                                        @default
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Bilinmiyor</span>
                                    @endswitch
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($order->total_price, 2) }}₺</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($order->status == 'pending')
                                        <form action="{{ route('order.approve', $order->id) }}" method="POST" class="inline-block mr-2">
                                            @csrf
                                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">Onayla</button>
                                        </form>

                                        <form action="{{ route('admin.order.cancel', $order->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">İptal Et</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
        </div>
        <!-- Pagination -->
        <div class="mt-6">
            {{ $orders->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startDate = document.querySelector('input[name="start_date"]');
            const endDate = document.querySelector('input[name="end_date"]');

            if(startDate && endDate) {
                startDate.addEventListener('change', function() {
                    endDate.min = this.value;
                });

                endDate.addEventListener('change', function() {
                    if(startDate.value && this.value < startDate.value) {
                        alert('Bitiş tarihi başlangıç tarihinden önce olamaz!');
                        this.value = '';
                    }
                });
            }
        });
    </script>
@endpush
