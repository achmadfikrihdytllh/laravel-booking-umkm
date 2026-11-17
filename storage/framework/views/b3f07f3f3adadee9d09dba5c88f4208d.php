<!doctype html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <?php echo $__env->make('partials.head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <title><?php echo $__env->yieldContent('title', 'Admin'); ?> - <?php echo e(setting('business_name')); ?></title>
    <meta name="robots" content="noindex">
</head>
<body>
<div class="adm">
    <aside class="side">
        <a class="brand" href="<?php echo e(route('admin.dashboard')); ?>">
            <?php if(public_url(setting('logo_path'))): ?><img class="brand__logo" src="<?php echo e(public_url(setting('logo_path'))); ?>" alt=""><?php endif; ?>
            <span><?php echo e(setting('business_name')); ?></span>
        </a>
        <?php ($nav = [
            ['admin.dashboard', 'nav_dashboard', 'admin.dashboard'],
            ['admin.bookings.index', 'nav_bookings', 'admin.bookings.*'],
            ['admin.services.index', 'nav_services', 'admin.services.*'],
            ['admin.staff.index', 'nav_staff', 'admin.staff.*'],
            ['admin.time-offs.index', 'nav_timeoffs', 'admin.time-offs.*'],
            ['admin.posts.index', 'nav_posts', 'admin.posts.*'],
            ['admin.settings.edit', 'nav_settings', 'admin.settings.*'],
        ]); ?>
        <?php $__currentLoopData = $nav; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$route, $label, $pattern]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a class="item <?php echo e(request()->routeIs($pattern) ? 'on' : ''); ?>" href="<?php echo e(route($route)); ?>"><?php echo e(__('admin.'.$label)); ?></a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <a class="item" href="<?php echo e(route('home')); ?>" target="_blank"><?php echo e(__('admin.view_site')); ?></a>
        <form method="post" action="<?php echo e(route('admin.logout')); ?>"><?php echo csrf_field(); ?><button class="btn btn--ghost btn--sm" type="submit"><?php echo e(__('admin.logout')); ?></button></form>
    </aside>
    <div class="main">
        <?php echo $__env->make('partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->yieldContent('content'); ?>
    </div>
</div>
</body>
</html>
<?php /**PATH C:\Users\Gigabyte\Documents\F's Projects\booking-umkm\resources\views/layouts/admin.blade.php ENDPATH**/ ?>