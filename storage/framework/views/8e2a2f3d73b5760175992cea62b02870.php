<?php $__env->startSection('title', __('admin.nav_posts')); ?>
<?php $__env->startSection('content'); ?>
<div class="row row--between"><h1><?php echo e(__('admin.nav_posts')); ?></h1><a class="btn btn--sm" href="<?php echo e(route('admin.posts.create')); ?>">+ <?php echo e(__('admin.add')); ?></a></div>
<div class="tablewrap"><table>
<thead><tr><th><?php echo e(__('admin.post_title')); ?></th><th><?php echo e(__('admin.status')); ?></th><th><?php echo e(__('admin.updated')); ?></th><th></th></tr></thead>
<tbody>
<?php $__empty_1 = true; $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<tr><td><?php echo e($p->title); ?></td><td><?php echo e($p->status === 'published' ? __('admin.published') : __('admin.draft')); ?></td><td><?php echo e($p->updated_at->format('d/m/Y H:i')); ?></td>
<td class="row"><a href="<?php echo e(route('admin.posts.edit', $p)); ?>"><?php echo e(__('admin.edit')); ?></a>
<form class="inline" method="post" action="<?php echo e(route('admin.posts.destroy', $p)); ?>" onsubmit="return confirm('<?php echo e(__('admin.delete_confirm')); ?>')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="btn btn--sm btn--danger" type="submit"><?php echo e(__('admin.delete')); ?></button></form></td></tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="4" class="muted"><?php echo e(__('admin.none')); ?></td></tr><?php endif; ?>
</tbody></table></div>
<div class="pager"><?php echo e($posts->links('pagination::simple-default')); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Gigabyte\Documents\F's Projects\booking-umkm\resources\views/admin/posts/index.blade.php ENDPATH**/ ?>