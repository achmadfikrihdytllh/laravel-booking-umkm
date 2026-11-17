<?php $__env->startSection('title', __('admin.nav_dashboard')); ?>
<?php $__env->startSection('content'); ?>
<h1><?php echo e(__('admin.nav_dashboard')); ?></h1>
<div class="stats">
    <div class="stat"><b><?php echo e($todayCount); ?></b><?php echo e(__('admin.today')); ?></div>
    <div class="stat"><b><?php echo e($pendingCount); ?></b><?php echo e(__('admin.pending')); ?></div>
    <div class="stat"><b><?php echo e($weekCount); ?></b><?php echo e(__('admin.this_week')); ?></div>
    <div class="stat"><b><?php echo e($monthCount); ?></b><?php echo e(__('admin.this_month')); ?></div>
</div>
<h2><?php echo e(__('admin.upcoming')); ?></h2>
<?php echo $__env->make('admin.bookings._table', ['bookings' => $upcoming], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Gigabyte\Documents\F's Projects\booking-umkm\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>