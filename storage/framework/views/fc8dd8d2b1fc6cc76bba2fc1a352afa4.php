<?php $__env->startSection('title', __('admin.nav_bookings')); ?>
<?php $__env->startSection('content'); ?>
<div class="row row--between"><h1><?php echo e(__('admin.nav_bookings')); ?></h1>
    <a class="btn btn--sm" href="<?php echo e(route('admin.bookings.export', request()->query())); ?>"><?php echo e(__('admin.export_csv')); ?></a></div>
<form method="get" class="filters">
    <input type="search" name="q" value="<?php echo e(request('q')); ?>" placeholder="<?php echo e(__('admin.search_ph')); ?>">
    <select name="status"><option value=""><?php echo e(__('admin.all_status')); ?></option>
        <?php $__currentLoopData = config('booking.statuses'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($s); ?>" <?php if(request('status') === $s): echo 'selected'; endif; ?>><?php echo e(__('booking.status.'.$s)); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
    <select name="staff_id"><option value=""><?php echo e(__('admin.all_staff')); ?></option>
        <?php $__currentLoopData = $staff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($p->id); ?>" <?php if(request('staff_id') == $p->id): echo 'selected'; endif; ?>><?php echo e($p->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
    <input type="date" name="from" value="<?php echo e(request('from')); ?>" aria-label="<?php echo e(__('admin.from')); ?>">
    <input type="date" name="to" value="<?php echo e(request('to')); ?>" aria-label="<?php echo e(__('admin.to')); ?>">
    <button class="btn btn--sm" type="submit"><?php echo e(__('admin.filter')); ?></button>
    <a class="btn btn--sm btn--ghost" href="<?php echo e(route('admin.bookings.index')); ?>"><?php echo e(__('admin.reset')); ?></a>
</form>
<?php echo $__env->make('admin.bookings._table', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<div class="pager"><?php echo e($bookings->links('pagination::simple-default')); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Gigabyte\Documents\F's Projects\booking-umkm\resources\views/admin/bookings/index.blade.php ENDPATH**/ ?>