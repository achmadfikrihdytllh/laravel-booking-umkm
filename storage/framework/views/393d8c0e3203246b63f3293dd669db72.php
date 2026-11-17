<?php $__env->startSection('title', __('admin.nav_settings')); ?>
<?php $__env->startSection('content'); ?>
<?php ($v = fn ($k) => old($k, setting($k))); ?>
<h1><?php echo e(__('admin.nav_settings')); ?></h1>
<form method="post" enctype="multipart/form-data" action="<?php echo e(route('admin.settings.update')); ?>" class="form">
    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
    <fieldset class="panel form"><legend><?php echo e(__('admin.brand')); ?></legend>
        <label><?php echo e(__('admin.business_name')); ?><input name="business_name" required maxlength="80" value="<?php echo e($v('business_name')); ?>"></label>
        <label><?php echo e(__('admin.tagline')); ?><input name="tagline" maxlength="160" value="<?php echo e($v('tagline')); ?>"></label>
        <label><?php echo e(__('admin.about')); ?><textarea name="about" rows="4"><?php echo e($v('about')); ?></textarea></label>
        <label><?php echo e(__('admin.logo')); ?><input type="file" name="logo" accept="image/*"></label>
        <?php if(public_url(setting('logo_path'))): ?>
            <div class="row"><img src="<?php echo e(public_url(setting('logo_path'))); ?>" alt="" height="56">
            <label class="chk"><input type="checkbox" name="remove_logo" value="1"> <?php echo e(__('admin.remove_logo')); ?></label></div>
        <?php endif; ?>
        <label><?php echo e(__('admin.brand_color')); ?><input type="color" name="brand_color" value="<?php echo e($v('brand_color')); ?>" style="height:44px;width:90px;padding:2px"></label>
    </fieldset>
    <fieldset class="panel form"><legend><?php echo e(__('admin.contact')); ?></legend>
        <div class="cols">
            <label><?php echo e(__('admin.whatsapp')); ?><input name="whatsapp" maxlength="20" value="<?php echo e($v('whatsapp')); ?>" placeholder="0812xxxxxxx"></label>
            <label><?php echo e(__('admin.phone')); ?><input name="phone" maxlength="30" value="<?php echo e($v('phone')); ?>"></label>
            <label><?php echo e(__('admin.email')); ?><input type="email" name="email" maxlength="120" value="<?php echo e($v('email')); ?>"></label>
            <label><?php echo e(__('admin.instagram')); ?><input name="instagram" maxlength="60" value="<?php echo e($v('instagram')); ?>"></label>
        </div>
        <label><?php echo e(__('admin.wa_greeting')); ?><input name="wa_greeting" maxlength="200" value="<?php echo e($v('wa_greeting')); ?>"></label>
        <label><?php echo e(__('admin.address')); ?><textarea name="address" rows="2"><?php echo e($v('address')); ?></textarea></label>
        <label><?php echo e(__('admin.maps_url')); ?><input type="url" name="maps_url" maxlength="500" value="<?php echo e($v('maps_url')); ?>"></label>
        <label><?php echo e(__('admin.opening_hours')); ?><textarea name="opening_hours" rows="2"><?php echo e($v('opening_hours')); ?></textarea></label>
    </fieldset>
    <fieldset class="panel form"><legend><?php echo e(__('admin.labels')); ?></legend>
        <p class="muted small"><?php echo e(__('admin.labels_hint')); ?></p>
        <div class="cols">
            <label><?php echo e(__('admin.staff_label_id')); ?><input name="staff_label_id" required maxlength="30" value="<?php echo e($v('staff_label_id')); ?>"></label>
            <label><?php echo e(__('admin.staff_label_en')); ?><input name="staff_label_en" required maxlength="30" value="<?php echo e($v('staff_label_en')); ?>"></label>
            <label><?php echo e(__('admin.service_label_id')); ?><input name="service_label_id" required maxlength="30" value="<?php echo e($v('service_label_id')); ?>"></label>
            <label><?php echo e(__('admin.service_label_en')); ?><input name="service_label_en" required maxlength="30" value="<?php echo e($v('service_label_en')); ?>"></label>
        </div>
    </fieldset>
    <fieldset class="panel form"><legend><?php echo e(__('admin.rules')); ?></legend>
        <div class="cols">
            <label><?php echo e(__('admin.slot_step')); ?><select name="slot_step"><?php $__currentLoopData = [10,15,20,30,45,60]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($n); ?>" <?php if((int) $v('slot_step') === $n): echo 'selected'; endif; ?>><?php echo e($n); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></label>
            <label><?php echo e(__('admin.min_notice_hours')); ?><input type="number" name="min_notice_hours" min="0" max="168" value="<?php echo e($v('min_notice_hours')); ?>"></label>
            <label><?php echo e(__('admin.max_advance_days')); ?><input type="number" name="max_advance_days" min="1" max="365" value="<?php echo e($v('max_advance_days')); ?>"></label>
        </div>
        <label class="chk"><input type="checkbox" name="auto_confirm" value="1" <?php if((int) setting('auto_confirm', 0) === 1): echo 'checked'; endif; ?>> <?php echo e(__('admin.auto_confirm')); ?></label>
    </fieldset>
    <div><button class="btn" type="submit"><?php echo e(__('admin.save')); ?></button></div>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Gigabyte\Documents\F's Projects\booking-umkm\resources\views/admin/settings.blade.php ENDPATH**/ ?>