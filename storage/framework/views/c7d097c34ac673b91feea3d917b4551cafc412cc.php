<nav class="navbar">
    <div class="nav-container">
        <div class="logo"><a href="<?php echo e(route('admin.dashboard')); ?>">HybridEstate</a></div>

        <div class="user-menu">
            <div class="menu-icon" onclick="toggleDropdown()">
                &#9776; <!-- Hamburger icon -->
            </div>
            <div id="dropdown" class="dropdown">
                <a href="#profile">
                    <i class="fas fa-user"></i> Profile
                </a>
                <a href="#settings">
                    <i class="fas fa-cog"></i> Settings
                </a>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav><?php /**PATH C:\xampp\htdocs\Web_Dev\Git_Demo\hybrid-property-system\resources\views/partials/dashboard_header.blade.php ENDPATH**/ ?>