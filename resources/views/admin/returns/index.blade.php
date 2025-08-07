@extends('admin.index')
@section('content')

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">İade Talepleri</h2>
                <div class="text-gray-600">Toplam: <span class="font-medium">{{ $returns->total() }}</span> kayıt</div>
            </div>
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 bg-white mb-1">Durum</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Tüm Durumlar</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Beklemede</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Onaylananlar</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Tamamlananlar</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Reddedilenler</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 bg-white mb-1">Başlangıç Tarihi</label>
                    <input type="date" name="start_date"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ request('start_date') }}">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 bg-white mb-1">Bitiş Tarihi</label>
                    <input type="date" name="end_date"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ request('end_date') }}">
                </div>

                <div class="flex space-x-2">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-all">
                        <i class="fas fa-filter mr-2"></i> Filtrele
                    </button>
                    @if(request()->has('status') || request()->has('start_date') || request()->has('end_date'))
                        <a href="{{ route('admin.returns.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-md transition-all">
                            <i class="fas fa-times mr-2"></i> Temizle
                        </a>
                    @endif
                </div>
            </form>
        </div>

        @if($returns->isEmpty())
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <div class="text-gray-400 mb-4">
                    <i class="fas fa-box-open text-5xl"></i>
                </div>
                <h4 class="text-lg font-medium text-gray-700 mb-2">Henüz iade talebi bulunmamaktadır</h4>
                <p class="text-gray-500">Müşterilerinizin iade talepleri burada listelenecektir</p>
            </div>
        @else
            <!-- Return Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($returns as $return)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden transition-all hover:shadow-lg border-l-8
                    @switch($return->status)
                        @case('pending') border-yellow-400 @break
                        @case('approved') border-blue-400 @break
                        @case('completed') border-green-400 @break
                        @case('rejected') border-red-400 @break
                    @endswitch">
                        <div class="p-5">
                            <!-- Card Header -->
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">İade #{{ $return->id }}</h3>
                                    <p class="text-sm text-gray-500">Sipariş #{{ $return->order_id }}</p>
                                </div>
                                <span class="status-badge
                                @switch($return->status)
                                    @case('pending') bg-yellow-100 text-yellow-800 @break
                                    @case('approved') bg-blue-100 text-blue-800 @break
                                    @case('completed') bg-green-100 text-green-800 @break
                                    @case('rejected') bg-red-100 text-red-800 @break
                                @endswitch">
                                @switch($return->status)
                                        @case('pending') Beklemede @break
                                        @case('approved') Onaylandı @break
                                        @case('completed') Tamamlandı @break
                                        @case('rejected') Reddedildi @break
                                    @endswitch
                            </span>
                            </div>

                            <!-- User Info -->
                            <div class="flex items-center mb-4">
                                <div class="user-avatar w-10 h-10 flex items-center justify-center bg-gray-100 text-gray-700 rounded-full font-semibold text-sm mr-3">
                                    {{ strtoupper(substr($return->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800">{{ $return->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $return->user->email }}</div>
                                </div>
                            </div>

                            <!-- Dates and Info -->
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <p class="text-xs text-gray-500">Talep Tarihi</p>
                                    <p class="text-sm font-medium text-gray-700">{{ $return->created_at->format('d.m.Y H:i') }}</p>
                                </div>
                                @if($return->status === 'approved')
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500">İade Kodu</p>
                                        <p class="text-sm font-bold text-gray-800">{{ $return->return_code }}</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Action Button -->
                            <div class="flex justify-end">
                                <a href="{{ route('admin.returns.show', $return->id) }}"
                                   class="text-blue-600 hover:text-blue-800 font-medium text-sm flex items-center transition-all">
                                    <i class="fas fa-eye mr-2"></i> Detayları Görüntüle
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $returns->appends(request()->query())->links() }}
            </div>
        @endif
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startDate = document.querySelector('input[name="start_date"]');
            const endDate = document.querySelector('input[name="end_date"]');

            startDate.addEventListener('change', function() {
                endDate.min = this.value;
            });

            endDate.addEventListener('change', function() {
                if(startDate.value && this.value < startDate.value) {
                    alert('Bitiş tarihi başlangıç tarihinden önce olamaz!');
                    this.value = '';
                }
            });
        });
    </script>
@endpush
