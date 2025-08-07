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


<header id="header" class="site-header text-black">
    <nav id="header-nav" class="navbar navbar-expand-lg">
        <div class="container-lg">
            <a class="navbar-brand" href="{{url('/')}}">
                <img src="{{ asset('images/logo.png') }}" class="logo" alt="logo">
            </a>
            <button class="navbar-toggler d-flex d-lg-none order-3 border-0 p-1 ms-2" type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#bdNavbar" aria-controls="bdNavbar" aria-expanded="false"
                    aria-label="Toggle navigation">
                <svg class="navbar-icon">
                    <use xlink:href="#navbar-icon"></use>
                </svg>
            </button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="bdNavbar">
                <div class="offcanvas-header px-4 pb-0">
                    <a class="navbar-brand ps-3" href="{{route('home')}}">
                        <img src="{{ asset('images/logo.png') }}" class="logo" alt="logo">
                    </a>
                    <button type="button" class="btn-close btn-close-black p-5" data-bs-dismiss="offcanvas"
                            aria-label="Close"
                            data-bs-target="#bdNavbar"></button>
                </div>
                <div class="offcanvas-body">
                    <ul id="navbar" class="navbar-nav fw-bold justify-content-end align-items-center flex-grow-1">
                        <li class="nav-item">
                            <a class="nav-link me-5" href="{{route('home')}}">Ana Sayfa</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link me-5" href="{{route('about_us')}}">Hakkımızda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link me-5" href="{{ route('new_products') }}">Yeni Gelenler</a>
                        </li>

                        @php
                            use App\Models\Category;
                            use App\Models\MainCategory;
                            $mainCategoriesWithSubs = MainCategory::with('categories')->get();
                            $standaloneCategories = Category::whereNull('main_category_id')->get();
                        @endphp

                            <!-- Ana kategoriler ve alt kategorileri listeleme -->
                        @foreach($mainCategoriesWithSubs as $mainCategory)
                            <li class="nav-item dropdown">
                                <a class="nav-link me-5 active dropdown-toggle border-0" href="#"
                                   data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ $mainCategory->name }}
                                </a>
                                <ul class="dropdown-menu fw-bold">
                                    @foreach($mainCategory->categories as $category)
                                        <li>
                                            <a href="{{ route('category_products', $category->slug) }}"
                                               class="dropdown-item">{{ $category->category_name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach

                        <!-- Bağımsız kategorileri listeleme -->
                        @foreach($standaloneCategories as $category)
                            <li class="nav-item">
                                <a class="nav-link me-5" href="{{ route('category_products', $category->slug) }}">
                                    {{ $category->category_name }}
                                </a>
                            </li>
                        @endforeach

                        <li class="nav-item">
                            <a class="nav-link me-5" href="{{route('contact_us')}}">Bize Ulaşın</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="user-items ps-0 ps-md-5">
                <ul class="d-flex justify-content-end list-unstyled align-item-center m-0">
                    <li class="nav-item dropdown pe-3">
                        <a class="nav-link dropdown-toggle border-0 text-dark" href="#" id="userDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <svg class="user" width="24" height="24">
                                <use xlink:href="#user"></use>
                            </svg>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            @if (Route::has('login'))
                                @auth
                                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Hesabım</a></li>
                                    <li><a class="dropdown-item" href="{{ route('user.orders') }}">Siparişlerim</a></li>
                                    <li><a class="dropdown-item" href="{{ route('user.address') }}">Adreslerim</a></li>
                                    <li><a class="dropdown-item" href="{{ route('user.coupons') }}">Kuponlarım</a></li>

                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">Çıkış Yap</button>
                                        </form>
                                    </li>
                                @else
                                    <li><a class="dropdown-item" href="{{ url('/login') }}">Giriş Yap</a></li>
                                    <li><a class="dropdown-item" href="{{ url('/register') }}">Kayıt Ol</a></li>
                                @endauth
                            @endif
                        </ul>
                    <li class="pe-3 position-relative">
                        @auth
                            <a href="{{ route('cart.index') }}" class="border-0 position-relative">
                                <svg class="shopping-cart" width="24" height="24">
                                    <use xlink:href="#shopping-cart">A</use>
                                </svg>
                                @if($count > 0)
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $count }}
                                    </span>
                                @endif
                            </a>
                        @endauth
                    </li>
                    <li class="pe-3 position-relative">
                        <a href="#" class="search-item border-0" data-bs-toggle="collapse" data-bs-target="#search-box"
                           aria-label="Toggle navigation">
                            <svg class="search" width="24" height="24">
                                <use xlink:href="#search"></use>
                            </svg>
                        </a>
                    </li>

                    <li class="w-100">
                        <div id="search-box" class="collapse search-box mt-2">
                            <form action="{{ route('search.products') }}" method="GET" class="d-flex">
                                <input type="text" name="q" class="form-control me-2" placeholder="Ürün ara..."
                                       required>
                                <button type="submit" class="btn btn-dark">Ara</button>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
