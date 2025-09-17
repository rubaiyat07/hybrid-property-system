<?php $__env->startSection('title', 'Homepage'); ?>

<?php $__env->startSection('content'); ?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Home</h1>
    <?php if($profile->screening_verified): ?>
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
            Verified Tenant
        </span>
    <?php endif; ?>
</div>
<div class="grid grid-cols-4 gap-6 mb-6">
    <div class="bg-white shadow rounded p-4 text-center">
        <h3 class="font-semibold text-gray-700">Active Leases</h3>
        <p class="text-2xl font-bold"><?php echo e($stats['active_leases']); ?></p>
    </div>
    <div class="bg-white shadow rounded p-4 text-center">
        <h3 class="font-semibold text-gray-700">Total Leases</h3>
        <p class="text-2xl font-bold"><?php echo e($stats['total_leases']); ?></p>
    </div>
    <div class="bg-white shadow rounded p-4 text-center">
        <h3 class="font-semibold text-gray-700">Pending Payments</h3>
        <p class="text-2xl font-bold"><?php echo e($stats['pending_payments']); ?></p>
    </div>
    <div class="bg-white shadow rounded p-4 text-center">
        <h3 class="font-semibold text-gray-700">Total Paid</h3>
        <p class="text-2xl font-bold">$<?php echo e(number_format($stats['total_paid'], 2)); ?></p>
    </div>
</div>

<div class="grid grid-cols-3 gap-6">
    <!-- Left (Main Content) -->
    <div class="col-span-2 space-y-6">

        <!-- 🔹 Current Lease -->
        <div class="bg-white shadow rounded p-4">
            <h3 class="font-semibold mb-4">Current Lease</h3>
            <?php if($currentLease): ?>
                <div class="border rounded p-4">
                    <h4 class="font-medium"><?php echo e($currentLease->unit->property->address); ?></h4>
                    <p class="text-sm text-gray-600">Unit: <?php echo e($currentLease->unit->unit_number); ?></p>
                    <p class="text-sm text-gray-600">Rent: $<?php echo e(number_format($currentLease->rent_amount, 2)); ?>/month</p>
                    <p class="text-sm text-gray-600">Start: <?php echo e($currentLease->start_date->format('M d, Y')); ?> - End: <?php echo e($currentLease->end_date->format('M d, Y')); ?></p>
                    <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700"><?php echo e(ucfirst($currentLease->status)); ?></span>
                </div>
            <?php else: ?>
                <p class="text-gray-500">No active lease found.</p>
            <?php endif; ?>
        </div>

        <!-- 🔹 Recent Payments -->
        <div class="bg-white shadow rounded p-4">
            <h3 class="font-semibold mb-4">Recent Payments</h3>
            <table class="w-full text-left border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2">Property</th>
                        <th class="px-4 py-2">Amount</th>
                        <th class="px-4 py-2">Due Date</th>
                        <th class="px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recentPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-t">
                            <td class="px-4 py-2"><?php echo e($payment->lease->unit->property->address ?? 'N/A'); ?></td>
                            <td class="px-4 py-2">$<?php echo e(number_format($payment->amount, 2)); ?></td>
                            <td class="px-4 py-2"><?php echo e($payment->due_date->format('M d, Y')); ?></td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-700"><?php echo e(ucfirst($payment->status)); ?></span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="4" class="px-4 py-2 text-center text-gray-500">No recent payments found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- 🔹 Upcoming Payments -->
        <div class="bg-white shadow rounded p-4">
            <h3 class="font-semibold mb-4">Upcoming Payments</h3>
            <table class="w-full text-left border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2">Property</th>
                        <th class="px-4 py-2">Amount</th>
                        <th class="px-4 py-2">Due Date</th>
                        <th class="px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $upcomingPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-t">
                            <td class="px-4 py-2"><?php echo e($payment->lease->unit->property->address ?? 'N/A'); ?></td>
                            <td class="px-4 py-2">$<?php echo e(number_format($payment->amount, 2)); ?></td>
                            <td class="px-4 py-2"><?php echo e($payment->due_date->format('M d, Y')); ?></td>
                            <td class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-700"><?php echo e(ucfirst($payment->status)); ?></span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="4" class="px-4 py-2 text-center text-gray-500">No upcoming payments found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- 🔹 Recent Maintenance Requests -->
        <div class="bg-white shadow rounded p-4">
            <h3 class="font-semibold mb-4">Recent Maintenance Requests</h3>
            <table class="w-full text-left border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2">Property</th>
                        <th class="px-4 py-2">Issue</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recentMaintenance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-t">
                            <td class="px-4 py-2"><?php echo e($request->unit->property->address ?? 'N/A'); ?></td>
                            <td class="px-4 py-2"><?php echo e(\Illuminate\Support\Str::limit($request->description, 50)); ?></td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 text-xs rounded <?php echo e($request->status == 'resolved' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'); ?>"><?php echo e(ucfirst($request->status)); ?></span>
                            </td>
                            <td class="px-4 py-2"><?php echo e($request->created_at->format('M d, Y')); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="4" class="px-4 py-2 text-center text-gray-500">No recent maintenance requests found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

    <!-- Right (Sidebar: Profile Brief) -->
    <div class="col-span-1">
        <?php echo $__env->make('partials.profile-brief', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.tenant', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Web_Dev\Git_Demo\hybrid-property-system\resources\views/tenant/homepage.blade.php ENDPATH**/ ?>