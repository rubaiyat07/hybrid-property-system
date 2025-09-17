<?php $__env->startSection('title', 'Property Details - ' . $property->name); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800"><?php echo e($property->name); ?></h1>
            <p class="text-gray-600 mt-1">Property ID: #<?php echo e($property->id); ?></p>
        </div>
        <div class="flex space-x-3">
            <a href="<?php echo e(route('landlord.property.index')); ?>" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md font-medium">
                <i class="fas fa-arrow-left mr-1"></i> Back to Properties
            </a>
            
            <?php if($property->registration_status !== 'rejected'): ?>
                <a href="<?php echo e(route('landlord.property.edit', $property)); ?>" 
                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md font-medium">
                    <i class="fas fa-edit mr-1"></i> Edit Property
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Registration Status Banner -->
    <div class="mb-6">
        <?php if($property->registration_status === 'pending'): ?>
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                <div class="flex items-center">
                    <i class="fas fa-clock mr-2"></i>
                    <div>
                        <strong>Registration Pending:</strong> Your property registration is under review by our admin team. 
                        You'll be notified once it's approved.
                    </div>
                </div>
            </div>
        <?php elseif($property->registration_status === 'approved'): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <div>
                            <strong>Property Approved:</strong> This property is approved and ready for unit management.
                            <?php if($property->approved_at): ?>
                                <br><small>Approved on <?php echo e($property->approved_at->format('M d, Y \a\t g:i A')); ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if($property->canAddUnits()): ?>
                        <a href="<?php echo e(route('landlord.units.create', ['property_id' => $property->id])); ?>" 
                           class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                            <i class="fas fa-plus mr-1"></i> Add Unit
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php elseif($property->registration_status === 'rejected'): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-times-circle mr-2"></i>
                        <div>
                            <strong>Registration Rejected:</strong> This property registration was not approved.
                            <?php if($property->registration_notes): ?>
                                <br><strong>Reason:</strong> <?php echo e($property->registration_notes); ?>

                            <?php endif; ?>
                        </div>
                    </div>
                    <form action="<?php echo e(route('landlord.property.resubmit', $property)); ?>" method="POST" class="inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm"
                                onclick="return confirm('Are you sure you want to resubmit this property for review?')">
                            <i class="fas fa-redo mr-1"></i> Resubmit
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Property Image -->
            <?php if($property->image): ?>
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <img src="<?php echo e($property->image); ?>" alt="<?php echo e($property->name); ?>" 
                         class="w-full h-64 object-cover">
                </div>
            <?php endif; ?>

            <!-- Property Details -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Property Information</h2>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Property Type</dt>
                            <dd class="text-sm text-gray-900"><?php echo e(ucfirst($property->type)); ?></dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Registration Status</dt>
                            <dd class="text-sm"><?php echo $property->registration_status_badge; ?></dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Address</dt>
                            <dd class="text-sm text-gray-900"><?php echo e($property->address); ?></dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Location</dt>
                            <dd class="text-sm text-gray-900"><?php echo e($property->city); ?>, <?php echo e($property->state); ?> <?php echo e($property->zip_code); ?></dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created Date</dt>
                            <dd class="text-sm text-gray-900"><?php echo e($property->created_at->format('M d, Y')); ?></dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                            <dd class="text-sm text-gray-900"><?php echo e($property->updated_at->format('M d, Y')); ?></dd>
                        </div>
                        <?php if($property->approved_at && $property->approver): ?>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Approved By</dt>
                                <dd class="text-sm text-gray-900"><?php echo e($property->approver->name); ?></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Approved Date</dt>
                                <dd class="text-sm text-gray-900"><?php echo e($property->approved_at->format('M d, Y \a\t g:i A')); ?></dd>
                            </div>
                        <?php endif; ?>
                    </dl>
                    
                    <?php if($property->description): ?>
                        <div class="mt-6">
                            <dt class="text-sm font-medium text-gray-500 mb-2">Description</dt>
                            <dd class="text-sm text-gray-900 leading-relaxed"><?php echo e($property->description); ?></dd>
                        </div>
                    <?php endif; ?>

                    <?php if($property->registration_notes && $property->registration_status === 'approved'): ?>
                        <div class="mt-6">
                            <dt class="text-sm font-medium text-gray-500 mb-2">Admin Notes</dt>
                            <dd class="text-sm text-gray-900 leading-relaxed bg-gray-50 p-3 rounded"><?php echo e($property->registration_notes); ?></dd>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Units Section -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-lg font-medium text-gray-900">Units</h2>
                    <?php if($property->canAddUnits()): ?>
                        <a href="<?php echo e(route('landlord.units.create', ['property_id' => $property->id])); ?>" 
                           class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-sm">
                            <i class="fas fa-plus mr-1"></i> Add Unit
                        </a>
                    <?php endif; ?>
                </div>
                <div class="p-6">
                    <?php if($property->canAddUnits()): ?>
                        <?php if($property->units && $property->units->count() > 0): ?>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Unit #</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Floor</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Size</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Rent</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php $__currentLoopData = $property->units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td class="px-4 py-2 text-sm font-medium text-gray-900"><?php echo e($unit->unit_number); ?></td>
                                                <td class="px-4 py-2 text-sm text-gray-900"><?php echo e($unit->floor ?? 'N/A'); ?></td>
                                                <td class="px-4 py-2 text-sm text-gray-900"><?php echo e($unit->size ?? 'N/A'); ?></td>
                                                <td class="px-4 py-2 text-sm text-gray-900">$<?php echo e(number_format($unit->rent_amount ?? 0)); ?></td>
                                                <td class="px-4 py-2 text-sm">
                                                    <?php if($unit->status === 'vacant'): ?>
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Vacant</span>
                                                    <?php elseif($unit->status === 'occupied'): ?>
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Occupied</span>
                                                    <?php else: ?>
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800"><?php echo e(ucfirst($unit->status)); ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="px-4 py-2 text-sm">
                                                    <a href="<?php echo e(route('landlord.units.show', $unit)); ?>" class="text-indigo-600 hover:text-indigo-900 mr-2" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="<?php echo e(route('landlord.units.edit', $unit)); ?>" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-8">
                                <i class="fas fa-home text-4xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500 mb-4">No units added yet.</p>
                                <a href="<?php echo e(route('landlord.units.create', ['property_id' => $property->id])); ?>" 
                                   class="inline-flex items-center text-indigo-600 hover:text-indigo-800">
                                    <i class="fas fa-plus mr-1"></i> Add your first unit
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <i class="fas fa-lock text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">Units can only be added to approved properties.</p>
                            <?php if($property->registration_status === 'pending'): ?>
                                <p class="text-sm text-yellow-600 mt-2">Your property is currently under review.</p>
                            <?php elseif($property->registration_status === 'rejected'): ?>
                                <p class="text-sm text-red-600 mt-2">Please address the rejection issues and resubmit.</p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Registration Status -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Registration Status</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Status</span>
                        <span class="text-sm font-medium"><?php echo $property->registration_status_badge; ?></span>
                    </div>
                    <?php if($property->approved_at): ?>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Approved</span>
                            <span class="text-sm font-medium text-gray-900"><?php echo e($property->approved_at->format('M d, Y')); ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Can Add Units</span>
                        <span class="text-sm font-medium <?php echo e($property->canAddUnits() ? 'text-green-600' : 'text-red-600'); ?>">
                            <?php echo e($property->canAddUnits() ? 'Yes' : 'No'); ?>

                        </span>
                    </div>
                </div>
            </div>

            <!-- Property Stats -->
            <?php if($property->is_approved): ?>
                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Property Stats</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Total Units</span>
                            <span class="text-sm font-medium text-gray-900"><?php echo e($property->units->count()); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Occupied Units</span>
                            <span class="text-sm font-medium text-gray-900"><?php echo e($property->units->where('status', 'occupied')->count()); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Vacant Units</span>
                            <span class="text-sm font-medium text-gray-900"><?php echo e($property->units->where('status', 'vacant')->count()); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Active Leases</span>
                            <span class="text-sm font-medium text-gray-900"><?php echo e($property->leases->where('end_date', '>=', now())->count()); ?></span>
                        </div>
                        <div class="border-t pt-4">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Occupancy Rate</span>
                                <span class="text-sm font-medium text-gray-900">
                                    <?php if($property->units->count() > 0): ?>
                                        <?php echo e(number_format(($property->units->where('status', 'occupied')->count() / $property->units->count()) * 100, 1)); ?>%
                                    <?php else: ?>
                                        0%
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <?php if($property->canAddUnits()): ?>
                        <a href="<?php echo e(route('landlord.units.create', ['property_id' => $property->id])); ?>" 
                           class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white text-center px-4 py-2 rounded-md text-sm">
                            <i class="fas fa-plus mr-1"></i> Add Unit
                        </a>
                        <a href="<?php echo e(route('landlord.leases.create', ['property_id' => $property->id])); ?>" 
                           class="block w-full bg-green-600 hover:bg-green-700 text-white text-center px-4 py-2 rounded-md text-sm">
                            <i class="fas fa-file-contract mr-1"></i> Create Lease
                        </a>
                    <?php endif; ?>
                    
                    <?php if($property->registration_status !== 'rejected'): ?>
                        <a href="<?php echo e(route('landlord.property.edit', $property)); ?>" 
                           class="block w-full bg-yellow-600 hover:bg-yellow-700 text-white text-center px-4 py-2 rounded-md text-sm">
                            <i class="fas fa-edit mr-1"></i> Edit Property
                        </a>
                    <?php endif; ?>

                    <?php if($property->registration_status === 'rejected'): ?>
                        <form action="<?php echo e(route('landlord.property.resubmit', $property)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" 
                                    class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center px-4 py-2 rounded-md text-sm"
                                    onclick="return confirm('Are you sure you want to resubmit this property for review?')">
                                <i class="fas fa-redo mr-1"></i> Resubmit for Review
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.landlord', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Web_Dev\Git_Demo\hybrid-property-system\resources\views/landlord/property/show.blade.php ENDPATH**/ ?>