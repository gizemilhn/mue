<aside class="w-72 h-screen bg-white text-gray-800 border-r fixed flex flex-col shadow-sm">

    <!-- Sidebar Header -->
    <div class="flex items-center gap-4 p-5 border-b">
        <img src="{{ asset('/admincss/img/avatar-6.jpg') }}"
             alt="Avatar"
             class="w-12 h-12 rounded-full object-cover">
        <div>
            <h1 class="text-base font-semibold">Mark Stephen</h1>
            <p class="text-sm text-gray-500">Web Designer</p>
        </div>
    </div>

    <!-- Sidebar Navigation -->
    <nav class="flex-1 overflow-y-auto px-4 py-6">
        <ul class="space-y-1.5 text-[15px] font-medium">

            <!-- Single Menu Item -->
            <li>
                <div class="{{ request()->is('admin.dashboard') ? 'bg-blue-50 border-l-4 border-blue-500' : '' }} rounded-lg">
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100 transition rounded-lg">
                        <i class="icon-home text-gray-600"></i>
                        <span>Dashboard</span>
                    </a>
                </div>
            </li>

            <!-- Collapsible Section: Categories -->
            <li>
                <button onclick="toggleMenu('categoriesCollapse')"
                        class="flex items-center justify-between w-full px-4 py-3 rounded-lg hover:bg-gray-100 transition">
                    <span class="flex items-center gap-3">
                        <i class="icon-windows text-gray-600"></i>
                        <span>Categories</span>
                    </span>
                    <i class="fa fa-chevron-down text-xs text-gray-500"></i>
                </button>
                <ul id="categoriesCollapse" class="ml-7 mt-1 space-y-1 hidden">
                    <li>
                        <a href="{{ route('main.category.index') }}"
                           class="block px-3 py-2 rounded-md hover:bg-gray-100 {{ request()->routeIs('main.category.index') ? 'bg-blue-50 border-l-2 border-blue-500 font-semibold' : '' }}">
                            Main Category
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('category.index') }}"
                           class="block px-3 py-2 rounded-md hover:bg-gray-100 {{ request()->routeIs('category.index') ? 'bg-blue-50 border-l-2 border-blue-500 font-semibold' : '' }}">
                            Category
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Tekli Menü Listesi (Loop ile) -->
            @php
                $menuItems = [
                    ['title' => 'Users', 'route' => 'admin.users.index', 'icon' => 'icon-users'],
                    ['title' => 'Orders', 'route' => 'admin.orders.index', 'icon' => 'icon-list'],
                    ['title' => 'Shippings', 'route' => 'admin.shippings.index', 'icon' => 'icon-truck'],
                    ['title' => 'Coupons', 'route' => 'admin.coupons.index', 'icon' => 'icon-tag'],
                    ['title' => 'Returns', 'route' => 'admin.returns.index', 'icon' => 'icon-rotate-ccw'],
                    ['title' => 'İletişim', 'route' => 'admin.contacts.index', 'icon' => 'icon-mail'],
                ];
            @endphp

            @foreach($menuItems as $item)
                <li>
                    <div class="{{ request()->routeIs($item['route']) ? 'bg-blue-50 border-l-4 border-blue-500' : '' }} rounded-lg">
                        <a href="{{ route($item['route']) }}"
                           class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100 transition rounded-lg">
                            <i class="{{ $item['icon'] }} text-gray-600"></i>
                            <span>{{ $item['title'] }}</span>
                        </a>
                    </div>
                </li>
            @endforeach

            <!-- Products Dropdown -->
            <li>
                <button onclick="toggleMenu('productsCollapse')"
                        class="flex items-center justify-between w-full px-4 py-3 rounded-lg hover:bg-gray-100 transition">
                    <span class="flex items-center gap-3">
                        <i class="icon-box text-gray-600"></i>
                        <span>Products</span>
                    </span>
                    <i class="fa fa-chevron-down text-xs text-gray-500"></i>
                </button>
                <ul id="productsCollapse" class="ml-7 mt-1 space-y-1 hidden">
                    <li>
                        <a href="{{ route('admin.products.store') }}"
                           class="block px-3 py-2 rounded-md hover:bg-gray-100 {{ request()->routeIs('admin.products.store') ? 'bg-blue-50 border-l-2 border-blue-500 font-semibold' : '' }}">
                            Add Product
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.product.index') }}"
                           class="block px-3 py-2 rounded-md hover:bg-gray-100 {{ request()->routeIs('admin.product.index') ? 'bg-blue-50 border-l-2 border-blue-500 font-semibold' : '' }}">
                            View Products
                        </a>
                    </li>
                </ul>
            </li>

        </ul>
    </nav>
</aside>
<script>
    function toggleMenu(id) {
        const el = document.getElementById(id);
        el.classList.toggle('hidden');
    }
</script>
