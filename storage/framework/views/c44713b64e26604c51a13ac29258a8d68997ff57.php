<?php $__env->startSection('content'); ?>
    <h1>Welcome, Admin!</h1>
    <p>Here is a quick overview:</p>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Total Properties</h5>
                    <p><?php echo e($totalProperties); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Total Users</h5>
                    <p><?php echo e($totalUsers); ?></p>
                </div>
            </div>
        </div>
        
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Web_Dev\Git_Demo\hybrid-property-system\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>