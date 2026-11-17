<div class="tablewrap">
<table>
    <thead><tr><th><?php echo e(__('admin.when')); ?></th><th><?php echo e(__('admin.customer')); ?></th><th><?php echo e(__('admin.service')); ?></th><th><?php echo e(staff_label()); ?></th><th><?php echo e(__('admin.status')); ?></th><th></th></tr></thead>
    <tbody>
    <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
            <td><?php echo e($b->starts_at->format('d/m/Y')); ?><br><span class="muted"><?php echo e($b->starts_at->format('H:i')); ?></span></td>
            <td><?php echo e($b->customer_name); ?><br><span class="muted"><?php echo e($b->customer_phone); ?></span></td>
            <td><?php echo e($b->service->name); ?></td>
            <td><?php echo e($b->staff->name); ?></td>
            <td><span class="badge badge--<?php echo e($b->status); ?>"><?php echo e(__('booking.status.'.$b->status)); ?></span></td>
            <td><a href="<?php echo e(route('admin.bookings.show', $b)); ?>"><?php echo e(__('admin.open')); ?></a></td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="6" class="muted"><?php echo e(__('admin.none')); ?></td></tr>
    <?php endif; ?>
    </tbody>
</table>
</div>
<?php /**PATH C:\Users\Gigabyte\Documents\F's Projects\booking-umkm\resources\views/admin/bookings/_table.blade.php ENDPATH**/ ?>