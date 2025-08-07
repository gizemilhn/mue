<header class="fixed top-0 left-72 right-0 h-24 bg-white shadow-sm z-30 px-6 flex items-center justify-between">
    <!-- Brand + Sidebar Toggle -->
    <div class="flex items-center space-x-4">
        <!-- Sidebar Toggle (mobil) -->
        <button class="text-gray-500 hover:text-gray-800 lg:hidden" id="sidebarToggle">
            <i class="fa fa-bars"></i>
        </button>

        <!-- Logo -->
        <a href="/" class="text-xl font-bold text-gray-800">
            <span class="text-emerald-800">Mue</span>Admin
        </a>
    </div>

    <!-- Search (only lg and up) -->
    <div class="hidden lg:flex items-center flex-grow justify-center">
        <form class="flex items-center w-full max-w-md" action="#" method="GET">
            <input type="search"
                   name="search"
                   placeholder="Ne arıyorsunuz?"
                   class="flex-grow px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-r-md hover:bg-blue-700 transition">
                Ara
            </button>
        </form>
    </div>

    <!-- Logout -->
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
