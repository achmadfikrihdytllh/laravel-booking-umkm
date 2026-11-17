<form method="post" action="<?php echo e(route('booking.store')); ?>" class="form" id="booking-form" novalidate
      data-slots-url="<?php echo e(route('slots')); ?>"
      data-t-pick="<?php echo e(__('booking.pick_first')); ?>" data-t-loading="<?php echo e(__('booking.loading')); ?>"
      data-t-none="<?php echo e(__('booking.no_slots')); ?>" data-t-fail="<?php echo e(__('booking.load_failed')); ?>"
      data-staff='<?php echo json_encode($services->mapWithKeys(fn ($s) => [$s->id => $s->staff->map(fn ($p) => ["id" => $p->id, "name" => $p->name])->values()]), 512) ?>'>
    <?php echo csrf_field(); ?>
    <div class="hp" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

    <label><?php echo e(__('booking.service')); ?>

        <select name="service_id" id="f-service" required>
            <option value=""><?php echo e(__('booking.choose_service')); ?></option>
            <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($s->id); ?>" <?php if(old('service_id') == $s->id): echo 'selected'; endif; ?>><?php echo e($s->name); ?> (<?php echo e(__('site.minutes', ['n' => $s->duration_minutes])); ?>)</option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </label>

    <label><?php echo e(staff_label()); ?>

        <select name="staff_id" id="f-staff">
            <option value=""><?php echo e(__('booking.any_staff')); ?></option>
        </select>
    </label>

    <label><?php echo e(__('booking.date')); ?>

        <input type="date" name="date" id="f-date" required value="<?php echo e(old('date')); ?>" min="<?php echo e(now()->toDateString()); ?>" max="<?php echo e($maxDate); ?>">
    </label>

    <fieldset class="slots">
        <legend><?php echo e(__('booking.time')); ?></legend>
        <p class="muted" id="slots-msg"><?php echo e(__('booking.pick_first')); ?></p>
        <div id="slots" role="radiogroup" aria-label="<?php echo e(__('booking.time')); ?>"></div>
        <input type="hidden" name="time" id="f-time" value="<?php echo e(old('time')); ?>">
    </fieldset>

    <label><?php echo e(__('booking.name')); ?>

        <input type="text" name="name" required maxlength="100" autocomplete="name" value="<?php echo e(old('name')); ?>">
    </label>
    <label><?php echo e(__('booking.phone')); ?>

        <input type="tel" name="phone" required inputmode="tel" autocomplete="tel" maxlength="20" value="<?php echo e(old('phone')); ?>">
        <small class="muted"><?php echo e(__('booking.phone_hint')); ?></small>
    </label>
    <label><?php echo e(__('booking.email')); ?>

        <input type="email" name="email" maxlength="150" autocomplete="email" value="<?php echo e(old('email')); ?>">
    </label>
    <label><?php echo e(__('booking.notes')); ?>

        <textarea name="notes" rows="3" maxlength="500" placeholder="<?php echo e(__('booking.notes_ph')); ?>"><?php echo e(old('notes')); ?></textarea>
    </label>

    <button class="btn" type="submit" id="f-submit"><?php echo e(__('booking.submit')); ?></button>
    <p class="muted small"><?php echo e(__('booking.privacy')); ?></p>
</form>
<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('js/booking.js')); ?>?v=1" defer></script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\Gigabyte\Documents\F's Projects\booking-umkm\resources\views/site/_form.blade.php ENDPATH**/ ?>