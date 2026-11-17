<!doctype html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head><?php echo $__env->make('partials.head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><title><?php echo e(__('admin.login')); ?> - <?php echo e(setting('business_name')); ?></title><meta name="robots" content="noindex"></head>
<body>
<div class="login">
    <h1><?php echo e(__('admin.login_title')); ?></h1>
    <?php echo $__env->make('partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <form method="post" action="<?php echo e(route('admin.login.post')); ?>" class="form">
        <?php echo csrf_field(); ?>
        <label>Email<input type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus autocomplete="username"></label>
        <label><?php echo e(__('admin.password')); ?><input type="password" name="password" required autocomplete="current-password"></label>
        <label class="chk"><input type="checkbox" name="remember" value="1"> <?php echo e(__('admin.remember')); ?></label>
        <button class="btn" type="submit"><?php echo e(__('admin.login')); ?></button>
    </form>
    <p><a href="<?php echo e(route('home')); ?>">&larr; <?php echo e(__('site.back')); ?></a></p>
</div>
</body>
</html>
<?php /**PATH C:\Users\Gigabyte\Documents\F's Projects\booking-umkm\resources\views/admin/login.blade.php ENDPATH**/ ?>