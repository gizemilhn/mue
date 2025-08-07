@extends('admin.index')
@section('content')
    <div class="p-6">
        <div class="bg-white rounded-lg shadow">
            <!-- Header Section -->
            <div class="px-6 pt-6 pb-4 border-b border-gray-200">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800">İade Talebi Detayı <span class="text-gray-500">#{{ $returnRequest->id }}</span></h2>
                        <p class="text-sm text-gray-500 mt-1">Oluşturulma: {{ $returnRequest->created_at->format('d.m.Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Main Content -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Status Card -->
                        <div class="rounded-lg p-6 shadow-sm
                        @switch($returnRequest->status)
                            @case('pending') bg-yellow-50/80 border border-yellow-200 @break
                            @case('approved') bg-green-50/80 border border-green-200 @break
                            @case('rejected') bg-red-50/80 border border-red-200 @break
                            @case('completed') bg-blue-50/80 border border-blue-200 @break
                        @endswitch">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 mb-2">İade Durumu</h3>
                                    <div class="flex items-center">
                                    <span class="px-3 py-1 rounded-full text-sm font-medium
                                        @switch($returnRequest->status)
                                            @case('pending') bg-yellow-100/90 text-yellow-800 @break
                                            @case('approved') bg-green-100/90 text-green-800 @break
                                            @case('rejected') bg-red-100/90 text-red-800 @break
                                            @case('completed') bg-blue-100/90 text-blue-800 @break
                                        @endswitch">
                                        @switch($returnRequest->status)
                                            @case('pending') Beklemede @break
                                            @case('approved') Onaylandı @break
                                            @case('rejected') Reddedildi @break
                                            @case('completed') Tamamlandı @break
                                        @endswitch
                                    </span>
                                        <span class="ml-3 text-sm text-gray-600">
                                        @switch($returnRequest->status)
                                                @case('pending') İade talebi değerlendirme bekliyor @break
                                                @case('approved') İade için kargo bilgileri oluşturuldu @break
                                                @case('rejected') İade talebi reddedildi @break
                                                @case('completed') İade süreci başarıyla tamamlandı @break
                                            @endswitch
                                    </span>
                                    </div>
                                </div>
                                @if($returnRequest->status === 'approved')
                                    <div class="mt-4 md:mt-0">
                                        <p class="text-sm font-medium text-gray-600">Kargo Kodu:</p>
                                        <p class="text-lg font-bold text-green-600">{{ $returnRequest->return_code }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Request Details Card -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Talep Detayları</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Kullanıcı</label>
                                        <p class="text-gray-800 font-medium">{{ $returnRequest->user->name }}</p>
                                        <p class="text-gray-600 text-sm">{{ $returnRequest->user->email }}</p>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Sipariş No</label>
                                        <p class="text-gray-800 font-medium">#{{ $returnRequest->order_id }}</p>
                                    </div>
                                </div>

                                <div>
                                    @if($returnRequest->status === 'approved')
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Kargo Firması</label>
                                            <p class="text-gray-800 font-medium">{{ $returnRequest->cargo_company }}</p>
                                        </div>
                                    @endif

                                    @if($returnRequest->status === 'rejected')
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Reddetme Sebebi</label>
                                            <p class="text-gray-800 font-medium">{{ $returnRequest->rejection_reason }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">İade Sebebi</label>
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                    <p class="text-gray-800">{{ $returnRequest->reason }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons (Conditional) -->
                        @if($returnRequest->status === 'pending')
                            <div class="bg-white rounded-lg shadow p-6 space-y-4">
                                <h3 class="text-lg font-semibold text-gray-800">İşlemler</h3>

                                <form method="POST" action="{{ route('admin.returns.approve', $returnRequest->id) }}" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label for="cargo_company" class="block text-sm font-medium text-gray-700 mb-1">Kargo Firması</label>
                                        <select name="cargo_company" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                            <option value="">Seçim yapın</option>
                                            <option value="Yurtiçi Kargo">Yurtiçi Kargo</option>
                                            <option value="MNG Kargo">MNG Kargo</option>
                                            <option value="Aras Kargo">Aras Kargo</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition-colors flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        İade Talebini Kabul Et
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.returns.reject', $returnRequest->id) }}" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-1">Reddetme Sebebi</label>
                                        <textarea name="rejection_reason" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Reddetme sebebini açıkça belirtin..." required></textarea>
                                    </div>
                                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md transition-colors flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                        İade Talebini Reddet
                                    </button>
                                </form>
                            </div>
                        @elseif($returnRequest->status === 'approved')
                            <div class="bg-white rounded-lg shadow p-6 text-center">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">İade Sürecini Tamamla</h3>
                                <form method="POST" action="{{ route('admin.returns.complete', $returnRequest->id) }}">
                                    @csrf
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md transition-colors inline-flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        İadeyi Tamamla
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                    <!-- Photos Sidebar -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow p-6 h-full flex flex-col">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Fotoğraflar</h3>

                            @if($returnRequest->photos)
                                @php
                                    $photos = is_string($returnRequest->photos)
                                        ? json_decode($returnRequest->photos, true)
                                        : $returnRequest->photos;
                                @endphp

                                <div class="grid grid-cols-2 gap-4 flex-grow">
                                    @foreach($photos as $photo)
                                        <a href="{{ Storage::url($photo) }}" data-lightbox="return-photos" class="block overflow-hidden rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                                            <img src="{{ Storage::url($photo) }}" class="w-full h-full object-cover hover:opacity-90 transition-opacity">
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <div class="flex-grow flex flex-col items-center justify-center text-gray-400 py-6">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-3 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-sm">Fotoğraf yüklenmemiş</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
