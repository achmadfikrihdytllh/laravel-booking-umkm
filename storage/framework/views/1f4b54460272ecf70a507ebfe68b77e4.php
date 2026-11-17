<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<?php if(public_url(setting('logo_path'))): ?>
<link rel="icon" href="<?php echo e(public_url(setting('logo_path'))); ?>">
<?php endif; ?>
<link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>?v=1">
<style>:root{--brand: <?php echo e(brand_color()); ?>;--on-brand: <?php echo e(on_brand()); ?>;}</style>
<?php /**PATH C:\Users\Gigabyte\Documents\F's Projects\booking-umkm\resources\views/partials/head.blade.php ENDPATH**/ ?>