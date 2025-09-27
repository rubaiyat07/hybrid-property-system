<header class="owner-header flex justify-between items-center px-8 py-4">
    <!-- Logo -->
    <div class="logo">
        <h2 class="text-2xl font-extrabold bg-gradient-to-r from-indigo-400 to-purple-600 
                   bg-clip-text text-transparent">
            @if(auth()->check())
                @if(auth()->user()->hasRole('Landlord'))
                    <a href="{{ route('landlord.homepage') }}">HybridEstate</a>
                @elseif(auth()->user()->hasRole('Agent'))
                    <a href="{{ route('agent.homepage') }}">HybridEstate</a>
                @elseif(auth()->user()->hasRole('Tenant'))
                    <a href="{{ route('tenant.homepage') }}">HybridEstate</a>
                @elseif(auth()->user()->hasRole('Buyer'))
                    <a href="{{ url('/') }}">HybridEstate</a>
                @else
                    <a href="{{ url('/') }}">HybridEstate</a>
                @endif
            @else
                <a href="{{ url('/') }}">HybridEstate</a>
            @endif
        </h2>
    </div>

    <!-- Mobile menu button -->
    <button class="mobile-menu-btn">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Nav Links -->
    <nav class="nav-links flex items-center gap-6">

        {{-- Landlord --}}
        @role('Landlord')
            <a href="{{ route('landlord.homepage') }}" class="relative font-medium text-black hover:text-indigo-400 transition">Home</a>
            
            <!-- Properties & Units Dropdown -->
            <div class="dropdown">
                <button class="dropdown-trigger relative font-medium text-black hover:text-indigo-400 transition">
                    Properties & Units <i class="fas fa-chevron-down ml-1"></i>
                </button>
                <div class="dropdown-menu">
                    <a href="{{ route('landlord.property.index') }}">
                        <i class="fas fa-building mr-2"></i> My Properties
                    </a>
                    <a href="{{ route('landlord.units.index') }}">
                        <i class="fas fa-home mr-2"></i> My Units
                    </a>
                </div>
            </div>
            
            <!-- Finance Dropdown -->
            <div class="dropdown">
                <button class="dropdown-trigger relative font-medium text-black hover:text-indigo-400 transition">
                    Finance <i class="fas fa-chevron-down ml-1"></i>
                </button>
                <div class="dropdown-menu">
                    <a href="{{ route('landlord.payments.index') }}">
                        <i class="fas fa-dollar-sign mr-2"></i> Payments
                    </a>
                    <a href="#">
                        <i class="fas fa-file-invoice-dollar mr-2"></i> Billings
                    </a>
                    <a href="#">
                        <i class="fas fa-receipt mr-2"></i> Invoices
                    </a>
                </div>
            </div>
            
            <!-- Tenants & Leases Dropdown -->
            <div class="dropdown">
                <button class="dropdown-trigger relative font-medium text-black hover:text-indigo-400 transition">
                    Tenants & Leases <i class="fas fa-chevron-down ml-1"></i>
                </button>
                <div class="dropdown-menu">
                    <a href="{{ route('landlord.tenants.index') }}">
                        <i class="fas fa-users mr-2"></i> Tenants
                    </a>
                    <a href="{{ route('landlord.leases.index') }}">
                        <i class="fas fa-file-contract mr-2"></i> Leases
                    </a>
                </div>
            </div>
        @endrole

        {{-- Agent --}}
        @role('Agent')
            <a href="{{ route('agent.homepage') }}" class="relative font-medium text-black hover:text-indigo-400 transition">Home</a>
            <a href="{{ route('agent.properties.index') }}" class="relative font-medium text-black hover:text-indigo-400 transition">Property Listings</a>
            <a href="#" class="relative font-medium text-black hover:text-indigo-400 transition">Clients</a>
            <a href="#" class="relative font-medium text-black hover:text-indigo-400 transition">Leads</a>
            <a href="#" class="relative font-medium text-black hover:text-indigo-400 transition">Deals</a>
        @endrole

        {{-- Tenant --}}
        @role('Tenant')
            <a href="{{ route('tenant.homepage') }}" class="relative font-medium text-black hover:text-indigo-400 transition">Home</a>
            <a href="{{ route('tenant.rentals.index') }}" class="relative font-medium text-black hover:text-indigo-400 transition">Find Rentals</a>
            <a href="#" class="relative font-medium text-black hover:text-indigo-400 transition">My Lease</a>
            <a href="#" class="relative font-medium text-black hover:text-indigo-400 transition">Payments</a>
            <a href="#" class="relative font-medium text-black hover:text-indigo-400 transition">Support</a>
        @endrole

        {{-- Buyer --}}
        @role('Buyer')
            <a href="#" class="relative font-medium text-black hover:text-indigo-400 transition">Browse Properties</a>
            <a href="#" class="relative font-medium text-black hover:text-indigo-400 transition">Saved Listings</a>
            <a href="#" class="relative font-medium text-black hover:text-indigo-400 transition">Inquiries</a>
        @endrole

        <!-- Search bar (common for all roles) -->
        <div class="search-bar flex items-center ml-4">
            <input type="text" id="search" name="search" placeholder="Search..."
                class="px-3 py-1 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-400">
            <button type="button" class="ml-2 text-indigo-500 hover:text-indigo-700" aria-label="Search">
                <i class="fas fa-search"></i>
            </button>
        </div>

        <!-- 👤 Profile dropdown -->
        <div class="profile-dropdown">
            <div class="profile-trigger cursor-pointer">
                @if(Auth::user()->profile_photo_url)
                    <img src="{{ Auth::user()->profile_photo_url }}" 
                         alt="Profile" class="w-10 h-10 rounded-full"/>
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
