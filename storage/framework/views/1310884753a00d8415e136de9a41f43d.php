<?php $__env->startSection('title', __('admin.nav_staff')); ?>
<?php $__env->startSection('content'); ?>
<div class="row row--between"><h1><?php echo e(__('admin.nav_staff')); ?> (<?php echo e(staff_label()); ?>)</h1><a class="btn btn--sm" href="<?php echo e(route('admin.staff.create')); ?>">+ <?php echo e(__('admin.add')); ?></a></div>
<div class="tablewrap"><table>
<thead><tr><th><?php echo e(__('admin.name')); ?></th><th><?php echo e(__('admin.title_role')); ?></th><th><?php echo e(__('admin.serves')); ?></th><th><?php echo e(__('admin.status')); ?></th><th></th></tr></thead>
<tbody>
<?php $__empty_1 = true; $__currentLoopData = $staffList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<tr><td><?php echo e($p->name); ?></td><td><?php echo e($p->title); ?></td><td><?php echo e($p->services->pluck('name')->join(', ')); ?></td><td><?php echo e($p->is_active ? __('admin.active') : __('admin.inactive')); ?></td>
<td class="row"><a href="<?php echo e(route('admin.staff.edit', $p)); ?>"><?php echo e(__('admin.edit')); ?></a>
<form class="inline" method="post" action="<?php echo e(route('admin.staff.destroy', $p)); ?>" onsubmit="return confirm('<?php echo e(__('admin.delete_confirm')); ?>')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="btn btn--sm btn--danger" type="submit"><?php echo e(__('admin.delete')); ?></button></form></td></tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="5" class="muted"><?php echo e(__('admin.none')); ?></td></tr><?php endif; ?>
</tbody></table></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Gigabyte\Documents\F's Projects\booking-umkm\resources\views/admin/staff/index.blade.php ENDPATH**/ ?>