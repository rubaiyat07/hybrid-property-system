<?php $__env->startSection('title', 'Tenant Details - HybridEstate'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Tenant Details</h1>
                <p class="text-gray-600 mt-1">View detailed information about the tenant</p>
            </div>
            <div class="flex space-x-3">
                <a href="<?php echo e(route('landlord.tenants.index')); ?>"
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Tenants
                </a>
            </div>
        </div>
    </div>

    <!-- Tenant Info Card -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white flex items-center">
                <i class="fas fa-user mr-2"></i>
                <?php echo e($tenant->user->first_name); ?> <?php echo e($tenant->user->last_name); ?>

            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex items-center">
                        <i class="fas fa-envelope text-gray-400 mr-3 w-5"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Email</p>
                            <p class="text-gray-900"><?php echo e($tenant->user->email); ?></p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-phone text-gray-400 mr-3 w-5"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Emergency Contact</p>
                            <p class="text-gray-900"><?php echo e($tenant->emergency_contact ?? 'N/A'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <i class="fas fa-calendar-check text-gray-400 mr-3 w-5"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Move In Date</p>
                            <p class="text-gray-900"><?php echo e($tenant->move_in_date ? \Carbon\Carbon::parse($tenant->move_in_date)->format('M d, Y') : 'N/A'); ?></p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-calendar-times text-gray-400 mr-3 w-5"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Move Out Date</p>
                            <p class="text-gray-900"><?php echo e($tenant->move_out_date ? \Carbon\Carbon::parse($tenant->move_out_date)->format('M d, Y') : 'N/A'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inquiries Section -->
    <?php if($inquiries->count() > 0 || $tenantLeads->count() > 0): ?>
    <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-yellow-600 to-orange-600 px-6 py-4">
            <h3 class="text-xl font-semibold text-white flex items-center">
                <i class="fas fa-question-circle mr-2"></i>
                Inquiries & Leads
            </h3>
        </div>
        <div class="p-6">
            <?php if($inquiries->count() > 0): ?>
                <h4 class="text-lg font-medium mb-4">Unit Inquiries</h4>
                <div class="space-y-4">
                    <?php $__currentLoopData = $inquiries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inquiry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-home text-gray-400 mr-2"></i>
                                    <span class="font-medium"><?php echo e($inquiry->unit->property->address); ?> - <?php echo e($inquiry->unit->unit_number); ?></span>
                                </div>
                                <p class="text-sm text-gray-600 mb-2"><?php echo e($inquiry->message); ?></p>
                                <div class="text-xs text-gray-500">
                                    <span>Inquired on <?php echo e($inquiry->created_at->format('M d, Y')); ?></span>
                                    <?php if($inquiry->preferred_viewing_date): ?>
                                        <span class="ml-4">Preferred date: <?php echo e($inquiry->preferred_viewing_date->format('M d, Y')); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <?php if($inquiry->status === 'pending'): ?>
                                    <?php if($inquiry->inquiry_type === 'booking_request'): ?>
                                        <form method="POST" action="<?php echo e(route('landlord.tenants.approve-inquiry', [$tenant, $inquiry])); ?>" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="inline-flex items-center px-3 py-1 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700">
                                                <i class="fas fa-check mr-1"></i> Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="<?php echo e(route('landlord.tenants.decline-inquiry', [$tenant, $inquiry])); ?>" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700">
                                                <i class="fas fa-times mr-1"></i> Decline
                                            </button>
                                        </form>
                                    <?php elseif($inquiry->inquiry_type === 'general_inquiry'): ?>
                                        <a href="<?php echo e(route('landlord.tenants.reply-inquiry', [$tenant, $inquiry])); ?>" class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                                            <i class="fas fa-reply mr-1"></i> Reply
                                        </a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        <?php if($inquiry->status === 'approved'): ?> bg-green-100 text-green-800
                                        <?php elseif($inquiry->status === 'declined'): ?> bg-red-100 text-red-800
                                        <?php elseif($inquiry->status === 'replied'): ?> bg-blue-100 text-blue-800
                                        <?php else: ?> bg-gray-100 text-gray-800 <?php endif; ?>">
                                        <?php echo e(ucfirst($inquiry->status)); ?>

                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            <?php if($tenantLeads->count() > 0): ?>
                <h4 class="text-lg font-medium mb-4 mt-6">Tenant Leads</h4>
                <div class="space-y-4">
                    <?php $__currentLoopData = $tenantLeads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lead): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <?php if($lead->unit): ?>
                                        <i class="fas fa-home text-gray-400 mr-2"></i>
                                        <span class="font-medium"><?php echo e($lead->unit->property->address); ?> - <?php echo e($lead->unit->unit_number); ?></span>
                                    <?php elseif($lead->property): ?>
                                        <i class="fas fa-building text-gray-400 mr-2"></i>
                                        <span class="font-medium"><?php echo e($lead->property->address); ?></span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-sm text-gray-600 mb-2"><?php echo e($lead->message); ?></p>
                                <div class="text-xs text-gray-500">
                                    <span>Lead created on <?php echo e($lead->created_at->format('M d, Y')); ?></span>
                                    <?php if($lead->budget_range): ?>
                                        <span class="ml-4">Budget: $<?php echo e($lead->budget_range); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <?php if(in_array($lead->status, ['new', 'contacted'])): ?>
                                    <form method="POST" action="<?php echo e(route('landlord.tenants.approve-lead', [$tenant, $lead])); ?>" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="inline-flex items-center px-3 py-1 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700">
                                            <i class="fas fa-check mr-1"></i> Approve
                                        </button>
                                    </form>
                                    <form method="POST" action="<?php echo e(route('landlord.tenants.decline-lead', [$tenant, $lead])); ?>" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700">
                                            <i class="fas fa-times mr-1"></i> Decline
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?php echo e($lead->status === 'qualified' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                        <?php echo e(ucfirst($lead->status)); ?>

                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Leases Section -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-green-600 to-teal-600 px-6 py-4">
            <h3 class="text-xl font-semibold text-white flex items-center">
                <i class="fas fa-file-contract mr-2"></i>
                Leases
            </h3>
        </div>
        <div class="p-6">
            <?php if($tenant->leases->count() > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monthly Rent</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__currentLoopData = $tenant->leases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lease): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <i class="fas fa-building text-gray-400 mr-2"></i>
                                    <?php echo e($lease->unit->property->address ?? 'N/A'); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <i class="fas fa-home text-gray-400 mr-2"></i>
                                    <?php echo e($lease->unit->unit_number ?? 'N/A'); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <i class="fas fa-calendar-plus text-gray-400 mr-2"></i>
                                    <?php echo e(\Carbon\Carbon::parse($lease->start_date)->format('M d, Y')); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <i class="fas fa-calendar-minus text-gray-400 mr-2"></i>
                                    <?php echo e(\Carbon\Carbon::parse($lease->end_date)->format('M d, Y')); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <i class="fas fa-dollar-sign text-gray-400 mr-2"></i>
                                    $<?php echo e(number_format($lease->monthly_rent, 2)); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if($lease->end_date >= now()): ?>
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i> Active
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i> Expired
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <i class="fas fa-file-contract text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">No leases found for this tenant.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Payments Section -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
            <h3 class="text-xl font-semibold text-white flex items-center">
                <i class="fas fa-credit-card mr-2"></i>
                Payments
            </h3>
        </div>
        <div class="p-6">
            <?php if($tenant->payments->count() > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__currentLoopData = $tenant->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <i class="fas fa-calendar-day text-gray-400 mr-2"></i>
                                    <?php echo e(\Carbon\Carbon::parse($payment->due_date)->format('M d, Y')); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <i class="fas fa-dollar-sign text-gray-400 mr-2"></i>
                                    $<?php echo e(number_format($payment->amount, 2)); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if($payment->status === 'paid'): ?>
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i> Paid
                                        </span>
                                    <?php elseif($payment->status === 'pending'): ?>
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i> Pending
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            <i class="fas fa-exclamation-triangle mr-1"></i> Overdue
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <i class="fas fa-credit-card text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">No payments found for this tenant.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.landlord', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Web_Dev\Git_Demo\hybrid-property-system\resources\views/landlord/tenants/show.blade.php ENDPATH**/ ?>