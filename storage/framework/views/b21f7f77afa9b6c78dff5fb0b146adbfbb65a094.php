<?php $__env->startSection('title', 'My Tenants - HybridEstate'); ?>

<?php $__env->startSection('content'); ?>
<div class="tenants-page">
    <!-- Page Header -->
    <div class="page-header mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">My Tenants</h1>
                <p class="text-gray-600 mt-1">Manage and view all your tenants</p>
            </div>
            <div class="flex gap-3">
                <button class="btn-secondary">
                    <i class="fas fa-download mr-2"></i>Export List
                </button>
                <a href="<?php echo e(route('landlord.tenants.create')); ?>" class="btn-primary">
                    <i class="fas fa-plus mr-2"></i>Add Tenant
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="stat-card bg-white p-6 rounded-lg shadow-sm border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Tenants</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e($stats['total_tenants'] ?? 0); ?></p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="stat-card bg-white p-6 rounded-lg shadow-sm border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active Leases</p>
                    <p class="text-3xl font-bold text-green-600"><?php echo e($stats['active_leases'] ?? 0); ?></p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-file-contract text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="stat-card bg-white p-6 rounded-lg shadow-sm border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pending Screening</p>
                    <p class="text-3xl font-bold text-yellow-600"><?php echo e($stats['pending_screening'] ?? 0); ?></p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="stat-card bg-white p-6 rounded-lg shadow-sm border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Overdue Payments</p>
                    <p class="text-3xl font-bold text-red-600"><?php echo e($stats['overdue_payments'] ?? 0); ?></p>
                </div>
                <div class="bg-red-100 p-3 rounded-full">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="filters-section bg-white p-4 rounded-lg shadow-sm border mb-6">
        <div class="flex flex-wrap gap-4 items-center">
            <div class="search-box">
                <input type="text" id="tenant-search" placeholder="Search tenants..."
                       class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                <i class="fas fa-search search-icon"></i>
            </div>

            <select id="property-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                <option value="">All Properties</option>
                <?php $__currentLoopData = $properties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($property->id); ?>"><?php echo e($property->address); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <select id="status-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="pending">Pending</option>
                <option value="expired">Expired</option>
            </select>

            <button class="btn-secondary" onclick="clearFilters()">
                <i class="fas fa-times mr-2"></i>Clear
            </button>
        </div>
    </div>

    <!-- Tenants Table -->
    <div class="tenants-table bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="table-header p-4 border-b bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900">Tenant List</h3>
        </div>

        <div class="table-responsive">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lease Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rent Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Move In Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $tenants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tenant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <?php if($tenant->user->profile_photo_url): ?>
                                        <img class="h-10 w-10 rounded-full object-cover"
                                             src="<?php echo e($tenant->user->profile_photo_url); ?>" alt="">
                                    <?php else: ?>
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                            <i class="fas fa-user text-gray-600"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?php echo e($tenant->user->first_name); ?> <?php echo e($tenant->user->last_name); ?>

                                    </div>
                                    <div class="text-sm text-gray-500"><?php echo e($tenant->user->email); ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                <?php echo e($tenant->leases->first()->unit->property->address ?? 'N/A'); ?>

                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                <?php echo e($tenant->leases->first()->unit->unit_number ?? 'N/A'); ?>

                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($tenant->leases->where('end_date', '>=', now())->count() > 0): ?>
                                <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                    Active
                                </span>
                            <?php elseif($tenant->leases->count() > 0): ?>
                                <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                    Expired
                                </span>
                            <?php else: ?>
                                <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                                    No Lease
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            $<?php echo e(number_format($tenant->leases->first()->monthly_rent ?? 0, 2)); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php
                                $latestPayment = $tenant->payments->sortByDesc('due_date')->first();
                                $isOverdue = $latestPayment && $latestPayment->status === 'pending' && $latestPayment->due_date < now();
                            ?>

                            <?php if($isOverdue): ?>
                                <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                    Overdue
                                </span>
                            <?php elseif($latestPayment && $latestPayment->status === 'paid'): ?>
                                <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                    Paid
                                </span>
                            <?php else: ?>
                                <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">
                                    Pending
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo e($tenant->move_in_date ? \Carbon\Carbon::parse($tenant->move_in_date)->format('M d, Y') : 'N/A'); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="<?php echo e(route('landlord.tenants.show', $tenant->id)); ?>" class="text-indigo-600 hover:text-indigo-900" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo e(route('landlord.tenants.edit', $tenant->id)); ?>" class="text-green-600 hover:text-green-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?php echo e(route('landlord.tenants.message', $tenant->id)); ?>" class="text-blue-600 hover:text-blue-900" title="Send Message">
                                    <i class="fas fa-envelope"></i>
                                </a>
                                <button class="text-red-600 hover:text-red-900"
                                        onclick="removeTenant(<?php echo e($tenant->id); ?>)" title="Remove">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-users text-4xl mb-4"></i>
                                <p class="text-lg">No tenants found</p>
                                <p class="text-sm">Add your first tenant to get started</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if(isset($tenants) && method_exists($tenants, 'links')): ?>
            <div class="px-6 py-3 border-t bg-gray-50">
                <?php echo e($tenants->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Tenant actions
function removeTenant(tenantId) {
    if (confirm('Are you sure you want to remove this tenant?')) {
        // Handle tenant removal
        fetch(`/landlord/tenants/${tenantId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            }
        });
    }
}

// Filter functions
function clearFilters() {
    document.getElementById('tenant-search').value = '';
    document.getElementById('property-filter').value = '';
    document.getElementById('status-filter').value = '';
}

// Search functionality
document.getElementById('tenant-search').addEventListener('input', function() {
    // Implement search logic
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.landlord', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Web_Dev\Git_Demo\hybrid-property-system\resources\views/landlord/tenants/index.blade.php ENDPATH**/ ?>