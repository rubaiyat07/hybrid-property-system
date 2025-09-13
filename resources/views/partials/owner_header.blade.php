<header class="owner-header flex justify-between items-center px-8 py-4">
    <!-- Logo -->
    <div class="logo">
        <h2 class="text-2xl font-extrabold bg-gradient-to-r from-indigo-400 to-purple-600 
                   bg-clip-text text-transparent">
            HybridEstate
        </h2>
    </div>

    <!-- Mobile menu button -->
    <button class="mobile-menu-btn">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Nav Links -->
    <nav class="nav-links flex items-center gap-6">
        <a href="#" class="relative font-medium text-black hover:text-indigo-400 transition">
            <i class="fas fa-building mr-1"></i> Properties
        </a>

        <a href="#" class="relative font-medium text-black hover:text-indigo-400 transition">
            <i class="fas fa-users mr-1"></i> Tenants
        </a>

        <a href="#" class="relative font-medium text-black hover:text-indigo-400 transition">
            <i class="fas fa-file-contract mr-1"></i> Leases
        </a>

        <a href="#" class="relative font-medium text-black hover:text-indigo-400 transition">
            <i class="fas fa-credit-card mr-1"></i> Payments
        </a>

        <a href="#" class="relative font-medium text-black hover:text-indigo-400 transition">
            <i class="fas fa-envelope mr-1"></i> Messages
        </a>

        <!-- 🔍 Search bar -->
        <div class="search-bar flex items-center ml-4">
            <input type="text" id="search" name="search" placeholder="Search..."
                class="px-3 py-1 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-400">
            <button type="button" class="ml-2 text-indigo-500 hover:text-indigo-700" aria-label="Search">
                <i class="fas fa-search"></i>
            </button>
        </div>

        <!-- 👤 Profile dropdown -->
        <div class="profile-dropdown">
            <div class="profile-trigger">
                @if(Auth::user()->profile_photo_url)
                    <img src="{{ Auth::user()->profile_photo_url }}" 
                         alt="Profile" />
                @else
                    <i class="fas fa-user-circle text-3xl text-gray-600"></i>
                @endif
            </div>

            <!-- Dropdown menu -->
            <div class="dropdown-menu">
                <a href="{{ route('profile.edit') }}">
                    <i class="fas fa-bell mr-1"></i> Notifications
                </a>
                <a href="{{ route('profile.edit') }}">
                    <i class="fas fa-user-cog mr-1"></i> Profile
                </a>
                <a href="{{ route('logout') }}" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt mr-1"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </div>
    </nav>       
</header>