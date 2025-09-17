<header class="owner-header flex justify-between items-center px-8 py-4">
    <!-- Logo -->
    <div class="logo">
        <h2 class="text-2xl font-extrabold bg-gradient-to-r from-indigo-400 to-purple-600 
                   bg-clip-text text-transparent">
            <?php if(auth()->check()): ?>
                <?php if(auth()->user()->hasRole('Landlord')): ?>
                    <a href="<?php echo e(route('landlord.homepage')); ?>">HybridEstate</a>
                <?php elseif(auth()->user()->hasRole('Agent')): ?>
                    <a href="<?php echo e(route('agent.homepage')); ?>">HybridEstate</a>
                <?php elseif(auth()->user()->hasRole('Tenant')): ?>
                    <a href="<?php echo e(route('tenant.homepage')); ?>">HybridEstate</a>
                <?php elseif(auth()->user()->hasRole('Buyer')): ?>
                    <a href="<?php echo e(url('/')); ?>">HybridEstate</a>
                <?php else: ?>
                    <a href="<?php echo e(url('/')); ?>">HybridEstate</a>
                <?php endif; ?>
            <?php else: ?>
                <a href="<?php echo e(url('/')); ?>">HybridEstate</a>
            <?php endif; ?>
        </h2>
    </div>

    <!-- Mobile menu button -->
    <button class="mobile-menu-btn">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Nav Links -->
    <nav class="nav-links flex items-center gap-6">

        
        <?php if (\Illuminate\Support\Facades\Blade::check('role', 'Landlord')): ?>
            <a href="<?php echo e(route('landlord.homepage')); ?>" class="relative font-medium text-black hover:text-indigo-400 transition">Home</a>
            <a href="<?php echo e(route('landlord.property.index')); ?>" class="relative font-medium text-black hover:text-indigo-400 transition">My Properties</a>
            <a href="<?php echo e(route('landlord.tenants.index')); ?>" class="relative font-medium text-black hover:text-indigo-400 transition">Tenants</a>
            <a href="<?php echo e(route('landlord.leases.index')); ?>" class="relative font-medium text-black hover:text-indigo-400 transition">Leases</a>
            <a href="#" class="relative font-medium text-black hover:text-indigo-400 transition">Payments</a>
        <?php endif; ?>

        
        <?php if (\Illuminate\Support\Facades\Blade::check('role', 'Agent')): ?>
            <a href="<?php echo e(route('agent.homepage')); ?>" class="relative font-medium text-black hover:text-indigo-400 transition">Home</a>
            <a href="<?php echo e(route('agent.properties.index')); ?>" class="relative font-medium text-black hover:text-indigo-400 transition">Property Listings</a>
            <a href="#" class="relative font-medium text-black hover:text-indigo-400 transition">Clients</a>
            <a href="#" class="relative font-medium text-black hover:text-indigo-400 transition">Leads</a>
            <a href="#" class="relative font-medium text-black hover:text-indigo-400 transition">Deals</a>
        <?php endif; ?>

        
        <?php if (\Illuminate\Support\Facades\Blade::check('role', 'Tenant')): ?>
            <a href="<?php echo e(route('tenant.homepage')); ?>" class="relative font-medium text-black hover:text-indigo-400 transition">Home</a>
            <a href="#" class="relative font-medium text-black hover:text-indigo-400 transition">Find Rentals</a>
            <a href="#" class="relative font-medium text-black hover:text-indigo-400 transition">My Lease</a>
            <a href="#" class="relative font-medium text-black hover:text-indigo-400 transition">Payments</a>
            <a href="#" class="relative font-medium text-black hover:text-indigo-400 transition">Support</a>
        <?php endif; ?>

        
        <?php if (\Illuminate\Support\Facades\Blade::check('role', 'Buyer')): ?>
            <a href="#" class="relative font-medium text-black hover:text-indigo-400 transition">Browse Properties</a>
            <a href="#" class="relative font-medium text-black hover:text-indigo-400 transition">Saved Listings</a>
            <a href="#" class="relative font-medium text-black hover:text-indigo-400 transition">Inquiries</a>
        <?php endif; ?>

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
                <?php if(Auth::user()->profile_photo_url): ?>
                    <img src="<?php echo e(Auth::user()->profile_photo_url); ?>" 
                         alt="Profile" class="w-10 h-10 rounded-full"/>
                <?php else: ?>
                    <i class="fas fa-user-circle text-3xl text-gray-600"></i>
                <?php endif; ?>
            </div>

            <!-- Dropdown menu -->
            <div class="dropdown-menu">
                <a href="<?php echo e(route('profile.edit')); ?>">
                    <i class="fas fa-bell mr-1"></i> Notifications
                </a>
                <a href="<?php echo e(route('profile.edit')); ?>">
                    <i class="fas fa-user-cog mr-1"></i> Profile
                </a>
                <a href="<?php echo e(route('logout')); ?>" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt mr-1"></i> Logout
                </a>
                <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="hidden">
                    <?php echo csrf_field(); ?>
                </form>
            </div>
        </div>
    </nav>       
</header>
<?php /**PATH C:\xampp\htdocs\Web_Dev\Git_Demo\hybrid-property-system\resources\views/partials/owner_header.blade.php ENDPATH**/ ?>