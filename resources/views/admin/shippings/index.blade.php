@extends('admin.index')
@section('content')
    <div class="p-6  min-h-screen">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-left">Kargo Takip</h2>

            <!-- Search and Filter Form -->
            <form method="GET" action="{{ route('admin.shippings.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div>
                    <input type="text" name="order_id" value="{{ request('order_id') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Sipariş No">
                </div>
                <div>
                    <input type="text" name="customer_name" value="{{ request('customer_name') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Müşteri Adı">
                </div>
                <div>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Tüm Durumlar</option>
                        <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>Hazırlanıyor</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Kargoya Verildi</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Teslim Edildi</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-300">
                        Filtrele
                    </button>
                </div>
            </form>

            <!-- Shipping Table -->
            <div class="overflow-x-auto rounded-lg shadow-sm">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sipariş No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Müşteri</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Takip No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Adres</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kargolama Tarihi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teslim Tarihi</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($shippings as $shipping)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $shipping->order_id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $shipping->order->user->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('admin.shippings.updateTracking', $shipping->id) }}" method="POST" class="flex items-center">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="tracking_number" value="{{ $shipping->tracking_number }}"
                                           class="flex-1 min-w-0 px-3 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                                    <button type="submit" class="ml-2 bg-blue-100 hover:bg-blue-200 text-blue-800 text-xs font-medium px-3 py-1 rounded-md transition duration-300">
                                        Güncelle
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $shipping->address->address_line }},
                                {{ $shipping->address->district }}/{{ $shipping->address->city }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('admin.shippings.updateStatus', $shipping->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" onchange="this.form.submit()"
                                            class="px-3 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                                        <option value="preparing" {{ $shipping->status == 'preparing' ? 'selected' : '' }}>Hazırlanıyor</option>
                                        <option value="shipped" {{ $shipping->status == 'shipped' ? 'selected' : '' }}>Kargoya Verildi</option>
                                        <option value="delivered" {{ $shipping->status == 'delivered' ? 'selected' : '' }}>Teslim Edildi</option>
                                        <option value="cancelled" {{ $shipping->status == 'cancelled' ? 'selected' : '' }}>İptal Edildi</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $shipping->shipped_at ? $shipping->shipped_at->format('d.m.Y H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $shipping->delivered_at ? $shipping->delivered_at->format('d.m.Y H:i') : '-' }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelects = document.querySelectorAll('select[name="status"]');

            statusSelects.forEach(select => {
                select.addEventListener('change', function(e) {
                    if (this.value === 'cancelled') {
                        if (!confirm('Bu sipariş durumunu "İptal Edildi" olarak değiştirmek istediğinize emin misiniz?')) {
                            this.form.reset();
                        }
                    }
                });
            });
        });
    </script>
@endpush
