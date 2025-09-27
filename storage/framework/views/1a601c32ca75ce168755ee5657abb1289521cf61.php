<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
            <p class="text-gray-600 mt-1">Welcome back! Here's an overview of your leases and payments.</p>
        </div>
        <?php if($profile->screening_verified): ?>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                Verified Tenant
            </span>
        <?php endif; ?>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-file-contract text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Leases</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e($stats['active_leases'] ?? 0); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-file-contract text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Leases</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e($stats['total_leases'] ?? 0); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Payments</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e($stats['pending_payments'] ?? 0); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-dollar-sign text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Paid</p>
                    <p class="text-3xl font-bold text-gray-900">৳<?php echo e(number_format($stats['total_paid'] ?? 0, 0, '.', ',')); ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Current Lease Summary -->
            <?php if($currentLease): ?>
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Current Lease</h2>
                </div>
                <div class="p-6">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 bg-indigo-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-home text-indigo-600 text-2xl"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900"><?php echo e($currentLease->unit->property->address); ?></h3>
                            <p class="text-sm text-gray-600">Unit <?php echo e($currentLease->unit->unit_number); ?> | <?php echo e($currentLease->unit->getDisplayPriceAttribute()); ?>/month</p>
                            <p class="text-sm text-gray-600"><?php echo e($currentLease->start_date->format('M d, Y')); ?> - <?php echo e($currentLease->end_date->format('M d, Y')); ?></p>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                <?php echo e(ucfirst($currentLease->status)); ?>

                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Sponsored Ads Carousel -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Sponsored Ads</h2>
                </div>
                <div class="p-6">
                    <div id="adsCarousel" class="relative overflow-hidden rounded-lg">
                        <div class="flex transition-transform duration-500" id="adsWrapper">
                            <?php $__empty_1 = true; $__currentLoopData = $ads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="min-w-full flex-shrink-0">
                                    <img src="<?php echo e($ad->image_url ?? asset('images/placeholder-ad.jpg')); ?>" alt="Ad" class="w-full h-48 object-cover rounded-lg">
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="min-w-full flex-shrink-0">
                                    <div class="w-full h-48 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <div class="text-center text-gray-500">
                                            <i class="fas fa-ad text-4xl mb-2"></i>
                                            <p>No ads available</p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if($ads->count() > 1): ?>
                            <!-- Carousel controls -->
                            <button onclick="prevAd()" class="absolute left-4 top-1/2 -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 transition">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button onclick="nextAd()" class="absolute right-4 top-1/2 -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 transition">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Active Listings (Available Rentals) -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-lg font-medium text-gray-900">Available Rentals</h2>
                    <a href="<?php echo e(route('tenant.rentals.index')); ?>" class="text-indigo-600 hover:text-indigo-900 text-sm">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rent</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__empty_1 = true; $__currentLoopData = $activeListings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $listing): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                    <i class="fas fa-building text-indigo-600"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900"><?php echo e($listing->property->address); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Unit <?php echo e($listing->unit_number); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo e($listing->getDisplayPriceAttribute()); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($listing->property->city); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="<?php echo e(route('inquiry.create', $listing->id)); ?>" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <i class="fas fa-paper-plane mr-1"></i>
                                            Apply
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="text-gray-500">
                                            <i class="fas fa-search text-4xl mb-4"></i>
                                            <p class="text-lg">No available rentals</p>
                                            <p class="text-sm">Browse rentals to find your next home</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Active Rentals (Tenant's Leases) -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-lg font-medium text-gray-900">Your Active Leases</h2>
                    <a href="<?php echo e(route('tenant.leases.index')); ?>" class="text-indigo-600 hover:text-indigo-900 text-sm">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rent</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__empty_1 = true; $__currentLoopData = $activeRentals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rental): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e($rental->unit->property->address); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Unit <?php echo e($rental->unit->unit_number); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo e($rental->unit->getDisplayPriceAttribute()); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <?php echo e(ucfirst($rental->status)); ?>

                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="text-gray-500">
                                            <i class="fas fa-file-contract text-4xl mb-4"></i>
                                            <p class="text-lg">No active leases</p>
                                            <p class="text-sm">Your leases will appear here</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <?php echo $__env->make('partials.profile-brief', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="<?php echo e(route('tenant.rentals.index')); ?>"
                       class="flex items-center w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                        <i class="fas fa-search mr-3"></i>
                        Find Rentals
                    </a>
                    <a href="<?php echo e(route('tenant.maintenance.create')); ?>"
                       class="flex items-center w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        <i class="fas fa-tools mr-3"></i>
                        Submit Maintenance
                    </a>
                    <a href="<?php echo e(route('tenant.payments.index')); ?>"
                       class="flex items-center w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                        <i class="fas fa-credit-card mr-3"></i>
                        View Payments
                    </a>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Activity</h3>
                <div class="space-y-3">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-dollar-sign text-green-600 text-sm"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900">Payment confirmed</p>
                            <p class="text-xs text-gray-500">2 hours ago</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-tools text-blue-600 text-sm"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900">Maintenance request submitted</p>
                            <p class="text-xs text-gray-500">1 day ago</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-search text-purple-600 text-sm"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900">Viewed rental listing</p>
                            <p class="text-xs text-gray-500">3 days ago</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Maintenance Requests -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Recent Maintenance</h3>
                </div>
                <div class="p-4 space-y-2 max-h-48 overflow-y-auto">
                    <?php $__empty_1 = true; $__currentLoopData = $recentMaintenance->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900"><?php echo e(Str::limit($request->description, 40)); ?></p>
                                <p class="text-xs text-gray-500"><?php echo e($request->unit->property->address); ?></p>
                            </div>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?php echo e($request->status == 'resolved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'); ?>">
                                <?php echo e(ucfirst($request->status)); ?>

                            </span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-4 text-gray-500">
                            <i class="fas fa-tools text-2xl mb-2"></i>
                            <p>No recent requests</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
let currentAd = 0;
const adsWrapper = document.getElementById('adsWrapper');
const totalAds = adsWrapper ? adsWrapper.children.length : 0;

function showAd(index) {
    if (totalAds === 0) return;
    currentAd = (index + totalAds) % totalAds;
    adsWrapper.style.transform = `translateX(-${currentAd * 100}%)`;
}

function nextAd() { showAd(currentAd + 1); }
function prevAd() { showAd(currentAd - 1); }

if (totalAds > 1) {
    setInterval(nextAd, 5000); // auto slide every 5s
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.tenant', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Web_Dev\Git_Demo\hybrid-property-system\resources\views/tenant/homepage.blade.php ENDPATH**/ ?>