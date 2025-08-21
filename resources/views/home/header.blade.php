<div class="preloader"
     style="position: fixed;top:0;left:0;width: 100%;height: 100%;background-color: #fff;display: flex;align-items: center;justify-content: center;z-index: 9999;">
    <svg version="1.1" id="L4" width="100" height="100" xmlns="http://www.w3.org/2000/svg"
         xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
         viewBox="0 0 50 100" enable-background="new 0 0 0 0" xml:space="preserve">
    <circle fill="#111" stroke="none" cx="6" cy="50" r="6">
        <animate
            attributeName="opacity"
            dur="1s"
            values="0;1;0"
            repeatCount="indefinite"
            begin="0.1"/>
    </circle>
        <circle fill="#111" stroke="none" cx="26" cy="50" r="6">
            <animate
                attributeName="opacity"
                dur="1s"
                values="0;1;0"
                repeatCount="indefinite"
                begin="0.2"/>
        </circle>
        <circle fill="#111" stroke="none" cx="46" cy="50" r="6">
            <animate
                attributeName="opacity"
                dur="1s"
                values="0;1;0"
                repeatCount="indefinite"
                begin="0.3"/>
        </circle>
    </svg>
</div>

<header id="header" class="site-header text-gray-900 bg-white shadow-sm sticky top-0 z-50">
    <nav class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
        <a class="block" href="{{url('/')}}">
            <img src="{{ asset('images/logo.png') }}" class="h-10" alt="logo">
        </a>

        <div class="hidden lg:flex items-center space-x-6 flex-grow justify-end">
            <ul class="flex items-center space-x-6 font-semibold">
                <li><a class="nav-link hover:text-gray-600 transition-colors" href="{{route('index')}}">Ana Sayfa</a></li>
                <li><a class="nav-link hover:text-gray-600 transition-colors" href="{{route('about_us')}}">Hakkımızda</a></li>
                <li><a class="nav-link hover:text-gray-600 transition-colors" href="{{ route('new_products') }}">Yeni Gelenler</a></li>

                @php
                    use App\Models\Category;
                    use App\Models\MainCategory;
                    $mainCategoriesWithSubs = MainCategory::with('categories')->get();
                    $standaloneCategories = Category::whereNull('main_category_id')->get();
                @endphp

                @foreach($mainCategoriesWithSubs as $mainCategory)
                    <li class="relative group">
                        <a class="nav-link hover:text-gray-600 transition-colors" href="#">
                            {{ $mainCategory->name }}
                        </a>
                        <div class="absolute hidden group-hover:block top-full -left-2 mt-0.5 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-10">
                            <ul class="py-2">
                                @foreach($mainCategory->categories as $category)
                                    <li>
                                        <a href="{{ route('category_products', $category->slug) }}"
                                           class="block px-4 py-2 hover:bg-gray-100 transition-colors">{{ $category->category_name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                @endforeach

                @foreach($standaloneCategories as $category)
                    <li>
                        <a class="nav-link hover:text-gray-600 transition-colors" href="{{ route('category_products', $category->slug) }}">
                            {{ $category->category_name }}
                        </a>
                    </li>
                @endforeach

                <li><a class="nav-link hover:text-gray-600 transition-colors" href="{{route('contact_us')}}">Bize Ulaşın</a></li>
            </ul>
        </div>

        <div class="flex items-center space-x-4 ml-auto lg:ml-6">
            <button class="text-gray-600 hover:text-gray-800 transition-colors focus:outline-none" onclick="toggleSearchBox()">
                <x-icon name="quick_view" class="w-6 h-6" />
            </button>

            <div class="relative group">
                <button class="text-gray-600 hover:text-gray-800 transition-colors focus:outline-none">
                    <x-icon name="user" class="w-6 h-6" />
                </button>
                <div class="absolute hidden group-hover:block top-full right-0 mt-0.5 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-10">
                    <ul class="py-2 text-sm font-semibold">
                        @if (Route::has('login'))
                            @auth

                                <li><a class="block px-4 py-2 hover:bg-gray-100" href="{{ route('user.orders') }}">Siparişlerim</a></li>
                                <li><a class="block px-4 py-2 hover:bg-gray-100" href="{{ route('user.address') }}">Adreslerim</a></li>
                                <li><a class="block px-4 py-2 hover:bg-gray-100" href="{{ route('user.coupons') }}">Kuponlarım</a></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-100">Çıkış Yap</button>
                                    </form>
                                </li>
                            @else
                                <li><a class="block px-4 py-2 hover:bg-gray-100" href="{{ url('/login') }}">Giriş Yap</a></li>
                                <li><a class="block px-4 py-2 hover:bg-gray-100" href="{{ url('/register') }}">Kayıt Ol</a></li>
                            @endauth
                        @endif
                    </ul>
                </div>
            </div>

            @auth
                <div class="relative">
                    <a href="{{ route('cart.index') }}" class="text-gray-600 hover:text-gray-800 transition-colors block">
                        <x-icon name="cart" class="w-6 h-6" />
                        @if(isset($count) && $count > 0)
                            <span class="absolute top-0 right-0 transform translate-x-1/2 -translate-y-1/2 bg-rose-600 text-white text-xs font-bold rounded-full h-4 w-4 flex items-center justify-center">{{ $count }}</span>
                        @endif
                    </a>
                </div>
            @endauth

            <button class="lg:hidden text-gray-600 hover:text-gray-800 transition-colors" onclick="openOffcanvas()">
                <x-icon name="navbar-toggle" class="w-6 h-6" />
            </button>
        </div>

        <div id="search-box" class="absolute hidden top-full left-0 w-full bg-white border-t border-gray-200 shadow-md py-4 transition-all duration-300">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <form action="{{ route('search.products') }}" method="GET" class="flex items-center">
                    <input type="text" name="q" class="flex-grow rounded-l-md border border-gray-300 py-2 px-4 focus:ring-rose-500 focus:border-rose-500" placeholder="Ürün ara..." required>
                    <button type="submit" class="bg-gray-900 text-white font-medium py-2 px-6 rounded-r-md hover:bg-gray-800 transition-colors">Ara</button>
                </form>
            </div>
        </div>
    </nav>
</header>

<div id="offcanvas-menu" class="fixed top-0 right-0 w-80 h-full bg-white z-[9999] transform translate-x-full transition-transform duration-300">
    <div class="px-6 py-4 flex items-center justify-between border-b border-gray-200">
        <a href="{{url('/')}}">
            <img src="{{ asset('images/logo.png') }}" class="h-8" alt="logo">
        </a>
        <button class="text-gray-600 hover:text-gray-800 focus:outline-none" onclick="closeOffcanvas()">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <ul class="px-6 py-4 space-y-4 font-semibold text-lg">
        <li><a class="block hover:text-gray-600 transition-colors" href="{{route('home')}}">Ana Sayfa</a></li>
        <li><a class="block hover:text-gray-600 transition-colors" href="{{route('about_us')}}">Hakkımızda</a></li>
        <li><a class="block hover:text-gray-600 transition-colors" href="{{ route('new_products') }}">Yeni Gelenler</a></li>

        @foreach($mainCategoriesWithSubs as $mainCategory)
            <li class="relative">
                <button class="w-full text-left flex justify-between items-center hover:text-gray-600 transition-colors" onclick="toggleMobileDropdown(this)">
                    <span>{{ $mainCategory->name }}</span>
                    <span>+</span>
                </button>
                <ul class="pl-4 mt-2 space-y-2 hidden">
                    @foreach($mainCategory->categories as $category)
                        <li>
                            <a href="{{ route('category_products', $category->slug) }}"
                               class="block text-sm text-gray-600 hover:text-gray-800">{{ $category->category_name }}</a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endforeach

        @foreach($standaloneCategories as $category)
            <li>
                <a class="block hover:text-gray-600 transition-colors" href="{{ route('category_products', $category->slug) }}">
                    {{ $category->category_name }}
                </a>
            </li>
        @endforeach

        <li><a class="block hover:text-gray-600 transition-colors" href="{{route('contact_us')}}">Bize Ulaşın</a></li>
    </ul>
</div>

@push('scripts')
    <script>
        // Search Box Toggle
        function toggleSearchBox() {
            const searchBox = document.getElementById('search-box');
            searchBox.classList.toggle('hidden');
            searchBox.classList.toggle('block');
        }

        // Offcanvas Menu
        const offcanvas = document.getElementById('offcanvas-menu');
        function openOffcanvas() {
            offcanvas.classList.remove('translate-x-full');
        }
        function closeOffcanvas() {
            offcanvas.classList.add('translate-x-full');
        }

        // Mobile Dropdown Menu
        function toggleMobileDropdown(button) {
            const dropdownList = button.nextElementSibling;
            const icon = button.querySelector('span:last-child');
            dropdownList.classList.toggle('hidden');
            icon.textContent = dropdownList.classList.contains('hidden') ? '+' : '-';
        }
    </script>
@endpush
