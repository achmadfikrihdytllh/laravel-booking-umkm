<?php $__env->startSection('content'); ?>
<section class="hero">
    <div class="wrap hero__in">
        <div>
            <h1><?php echo e(setting('business_name')); ?></h1>
            <p class="lead"><?php echo e(setting('tagline')); ?></p>
            <div class="row">
                <a class="btn" href="#reservasi"><?php echo e(__('site.hero_cta')); ?></a>
                <?php if($wa = wa_link(setting('whatsapp'), setting('wa_greeting'))): ?>
                    <a class="btn btn--ghost" href="<?php echo e($wa); ?>" target="_blank" rel="noopener"><?php echo e(__('site.hero_secondary')); ?></a>
                <?php endif; ?>
            </div>
        </div>
        <?php if(public_url(setting('logo_path'))): ?>
            <img class="hero__logo" src="<?php echo e(public_url(setting('logo_path'))); ?>" alt="">
        <?php endif; ?>
    </div>
</section>

<?php if(setting('about')): ?>
<section class="section" id="tentang">
    <div class="wrap narrow">
        <h2><?php echo e(__('site.about_title')); ?></h2>
        <p class="prose"><?php echo nl2br(e(setting('about'))); ?></p>
    </div>
</section>
<?php endif; ?>

<section class="section section--alt" id="layanan">
    <div class="wrap">
        <h2><?php echo e(__('site.services_title')); ?></h2>
        <p class="muted"><?php echo e(__('site.services_sub')); ?></p>
        <div class="grid">
            <?php $__empty_1 = true; $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <article class="card">
                    <h3><?php echo e($s->name); ?></h3>
                    <?php if($s->description): ?><p><?php echo e($s->description); ?></p><?php endif; ?>
                    <p class="meta"><?php echo e(__('site.minutes', ['n' => $s->duration_minutes])); ?><?php if($s->price_label): ?> &middot; <?php echo e($s->price_label); ?><?php endif; ?></p>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="muted"><?php echo e(__('site.no_services')); ?></p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php if($staff->isNotEmpty()): ?>
<section class="section" id="tim">
    <div class="wrap">
        <h2><?php echo e(__('site.team_title')); ?></h2>
        <div class="grid grid--people">
            <?php $__currentLoopData = $staff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <article class="person">
                    <?php if($p->photo_url): ?>
                        <img src="<?php echo e($p->photo_url); ?>" alt="<?php echo e($p->name); ?>" loading="lazy">
                    <?php else: ?>
                        <div class="person__ph" aria-hidden="true"><?php echo e(mb_strtoupper(mb_substr($p->name, 0, 1))); ?></div>
                    <?php endif; ?>
                    <h3><?php echo e($p->name); ?></h3>
                    <?php if($p->title): ?><p class="meta"><?php echo e($p->title); ?></p><?php endif; ?>
                    <?php if($p->bio): ?><p><?php echo e($p->bio); ?></p><?php endif; ?>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="section section--alt" id="reservasi">
    <div class="wrap narrow">
        <h2><?php echo e(__('booking.title')); ?></h2>
        <p class="muted"><?php echo e(__('booking.subtitle')); ?></p>
        <?php echo $__env->make('partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('site._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
</section>

<?php if($posts->isNotEmpty()): ?>
<section class="section" id="berita">
    <div class="wrap">
        <div class="row row--between">
            <h2><?php echo e(__('site.news_title')); ?></h2>
            <a href="<?php echo e(route('posts.index')); ?>"><?php echo e(__('site.news_all')); ?> &rarr;</a>
        </div>
        <div class="grid">
            <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('site._post_card', ['post' => $p], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="section section--alt" id="kontak">
    <div class="wrap">
        <h2><?php echo e(__('site.contact_title')); ?></h2>
        <div class="grid grid--2">
            <div>
                <?php if(setting('address')): ?><p><strong><?php echo e(__('site.address')); ?></strong><br><?php echo nl2br(e(setting('address'))); ?></p><?php endif; ?>
                <?php if(setting('opening_hours')): ?><p><strong><?php echo e(__('site.hours')); ?></strong><br><?php echo nl2br(e(setting('opening_hours'))); ?></p><?php endif; ?>
                <?php if(setting('maps_url')): ?><p><a href="<?php echo e(setting('maps_url')); ?>" target="_blank" rel="noopener"><?php echo e(__('site.open_map')); ?> &rarr;</a></p><?php endif; ?>
            </div>
            <div>
                <?php if(setting('phone')): ?><p>Tel: <a href="tel:<?php echo e(preg_replace('/[^0-9+]/', '', setting('phone'))); ?>"><?php echo e(setting('phone')); ?></a></p><?php endif; ?>
                <?php if(setting('email')): ?><p>Email: <a href="mailto:<?php echo e(setting('email')); ?>"><?php echo e(setting('email')); ?></a></p><?php endif; ?>
                <?php if(setting('instagram')): ?><p>Instagram: <a href="https://instagram.com/<?php echo e(ltrim(setting('instagram'), '@')); ?>" target="_blank" rel="noopener">&#64;<?php echo e(ltrim(setting('instagram'), '@')); ?></a></p><?php endif; ?>
                <?php if($wa): ?><p><a class="btn" href="<?php echo e($wa); ?>" target="_blank" rel="noopener"><?php echo e(__('site.chat_wa')); ?></a></p><?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.site', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Gigabyte\Documents\F's Projects\booking-umkm\resources\views/site/home.blade.php ENDPATH**/ ?>