<header class="fixed top-0 left-72 right-0 h-24 bg-white shadow-sm z-30 px-6 flex items-center justify-between">
    <div class="flex items-center space-x-4">
        <button class="text-gray-500 hover:text-gray-800 lg:hidden" id="sidebarToggle">
            <i class="fa fa-bars"></i>
        </button>

        <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-gray-800">
            <span class="text-emerald-800">Mue</span>Admin
        </a>
    </div>

    <div class="relative">
        <input type="text" id="global-search" placeholder="Ara..." class="border rounded p-2 w-64">
        <div id="search-results" class="absolute top-full mt-2 left-0 bg-white border rounded w-64 shadow-lg z-40 hidden"></div>
    </div>

    <div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="text-sm text-red-600 hover:text-red-800 border border-red-600 px-4 py-1 rounded-md transition">
                Çıkış Yap
            </button>
        </form>
    </div>
</header>

@push('scripts')
    <script>
        document.getElementById('global-search').addEventListener('input', function(e) {
            let query = e.target.value;
            let searchResults = document.getElementById('search-results');

            if (query.length < 2) {
                searchResults.classList.add('hidden');
                return;
            }

            fetch(`{{ route('admin.global-search') }}?q=${encodeURIComponent(query)}`)
                .then(res => {
                    if (!res.ok) {
                        throw new Error(`HTTP error! status: ${res.status}`);
                    }
                    return res.json();
                })
                .then(data => {
                    let html = '';
                    let hasResults = false;

                    if (data.users && data.users.length) {
                        html += `<div class="p-2 font-bold text-sm text-gray-700">Kullanıcılar</div>`;
                        data.users.forEach(u => {
                            html += `<div class="p-2 text-sm hover:bg-gray-100 cursor-pointer" onclick="window.location='{{ route('admin.users.index') }}?search=${encodeURIComponent(u.name)}'">${u.name}</div>`;
                        });
                        hasResults = true;
                    }

                    if (data.products && data.products.length) {
                        html += `<div class="p-2 font-bold text-sm text-gray-700">Ürünler</div>`;
                        data.products.forEach(p => {
                            html += `<div class="p-2 text-sm hover:bg-gray-100 cursor-pointer" onclick="window.location='{{ route('product_search') }}?search=${encodeURIComponent(p.name)}'">${p.name}</div>`;
                        });
                        hasResults = true;
                    }

                    if (data.contacts && data.contacts.length) {
                        html += `<div class="p-2 font-bold text-sm text-gray-700">İletişim Formları</div>`;
                        data.contacts.forEach(c => {
                            html += `<div class="p-2 text-sm hover:bg-gray-100 cursor-pointer" onclick="window.location='{{ route('admin.contacts.index') }}?search=${encodeURIComponent(c.name)}'">${c.name}</div>`;
                        });
                        hasResults = true;
                    }

                    if (data.orders && data.orders.length) {
                        html += `<div class="p-2 font-bold text-sm text-gray-700">Siparişler</div>`;
                        data.orders.forEach(o => {
                            html += `<div class="p-2 text-sm hover:bg-gray-100 cursor-pointer" onclick="window.location='{{ route('admin.orders.index') }}?search=${encodeURIComponent(o.id)}'">${o.id}</div>`;
                        });
                        hasResults = true;
                    }

                    // İade Talepleri (Düzeltildi)
                    if (data.returns && data.returns.length) {
                        html += `<div class="p-2 font-bold text-sm text-gray-700">İade Talepleri</div>`;
                        data.returns.forEach(r => {
                            html += `<div class="p-2 text-sm hover:bg-gray-100 cursor-pointer" onclick="window.location='{{ route('admin.returns.index') }}?user_search=${encodeURIComponent(r.return_code)}'">${r.return_code}</div>`;
                        });
                        hasResults = true;
                    }

                    if (!hasResults) {
                        html += `<div class="p-2 text-sm text-gray-500">Sonuç bulunamadı.</div>`;
                    }

                    searchResults.innerHTML = html;
                    searchResults.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Arama sırasında bir hata oluştu:', error);
                    searchResults.innerHTML = `<div class="p-2 text-red-500 text-sm">Arama sırasında bir hata oluştu.</div>`;
                    searchResults.classList.remove('hidden');
                });
        });

        document.addEventListener('click', function(e) {
            const searchContainer = document.querySelector('.relative');
            if (!searchContainer.contains(e.target)) {
                document.getElementById('search-results').classList.add('hidden');
            }
        });
    </script>
@endpush
