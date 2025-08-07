@extends('admin.index')
@section('content')
    <div class="p-4 ">
        <div class="p-4">
            <div class="bg-white rounded-lg shadow-md ">
                <div class="p-6">
                    <!-- Enhanced Header Section -->
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                        <div>
                            <h1 class="text-2xl font-semibold text-gray-800">Yeni Kupon Oluştur</h1>
                            <p class="text-sm text-gray-500 mt-1">Yeni kupon bilgilerini aşağıdaki formu doldurarak ekleyebilirsiniz</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="{{ URL::previous() }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                                </svg>
                                Geri Dön
                            </a>
                        </div>
                    </div>
                    <form action="{{ route('admin.coupons.store') }}" method="POST" class="space-y-6 ">
                        @csrf

                        <!-- Coupon Code -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Kupon Kodu</label>
                            <div class="relative">
                                <input type="text" name="code" id="code"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       required placeholder="KUPONKODU123">
                                <button type="button" id="generate-code"
                                        class="absolute right-2 top-2 px-3 py-1 bg-gray-100 text-gray-700 text-sm font-medium rounded hover:bg-gray-200 transition-colors">
                                    Kod Üret
                                </button>
                            </div>
                        </div>

                        <!-- Discount Type and Value -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="">
                                <label class="block text-sm font-medium text-gray-700">İndirim Türü</label>
                                <select name="discount_type" id="discount_type"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                    <option value="percent">Yüzde İndirim</option>
                                    <option value="amount">Sabit Tutar</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">İndirim Değeri</label>
                                <div class="flex rounded-md ">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm"
                                          id="discount-symbol"></span>
                                    <input type="number" name="discount_value" id="discount_value"
                                           class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           required min="1">
                                </div>
                            </div>
                        </div>

                        <!-- Usage Limit and Expiry Date -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Kullanım Limiti</label>
                                <input type="number" name="usage_limit"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="Boş bırakılırsa sınırsız">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Son Kullanma Tarihi</label>
                                <input type="date" name="expires_at"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <!-- Specific User -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Özel Kullanıcı</label>
                            <select name="user_id" class="w-full select2 rounded p-2 border">
                                <option value="">Tüm Kullanıcılar</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status Toggle -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Durum</label>
                            <div class="flex items-center">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1" class="sr-only peer" checked>
                                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    <span class="ml-3 text-sm font-medium text-gray-700">Kupon Aktif</span>
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4">
                            <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                Kuponu Kaydet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            // Select2 initialization
            $('.select2').select2({
                placeholder: "Seçiniz...",
                width: '100%',
                minimumResultsForSearch: 5,
                $dropdownParent:$('form')
            });

            // Set initial symbol
            const initialType = $('#discount_type').val();
            const initialSymbol = initialType === 'percent' ? '%' : '₺';
            $('#discount-symbol').text(initialSymbol);

            // On discount type change
            $('#discount_type').change(function () {
                const type = $(this).val();
                const symbol = type === 'percent' ? '%' : '₺';
                $('#discount-symbol').text(symbol);

                const minValue = type === 'percent' ? 1 : 5;
                $('#discount_value').attr('min', minValue);
            });

            // Generate random coupon code
            $('#generate-code').click(function () {
                const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
                let result = '';
                for (let i = 0; i < 8; i++) {
                    result += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                $('#code').val(result);
            });
        });
    </script>

@endpush
