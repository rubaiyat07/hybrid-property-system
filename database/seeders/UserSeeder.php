<header class="owner-header">
    <div class="logo">
        <h2>HybridEstate</h2>
    </div>

    <nav class="nav-links">
        <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>

        {{-- Landlord specific --}}
        @if(Auth::user()->hasRole('landlord'))
            <a href="{{ route('landlord.properties') }}"><i class="fas fa-building"></i> Properties</a>
            <a href="{{ route('landlord.tenants') }}"><i class="fas fa-users"></i> Tenants</a>
            <a href="{{ route('landlord.leases') }}"><i class="fas fa-file-contract"></i> Leases</a>
            <a href="{{ route('landlord.payments') }}"><i class="fas fa-credit-card"></i> Payments</a>
        @endif

        {{-- Agent specific --}}
        @if(Auth::user()->hasRole('agent'))
            <a href="{{ route('agent.properties') }}"><i class="fas fa-briefcase"></i> Assigned Properties</a>
            <a href="{{ route('agent.clients') }}"><i class="fas fa-user-tie"></i> Clients</a>
            <a href="{{ route('agent.reports') }}"><i class="fas fa-chart-line"></i> Reports</a>
        @endif

        {{-- Common --}}
        <a href="{{ route('profile.edit') }}"><i class="fas fa-user-cog"></i> Profile</a>
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </nav>
</header>
