<article class="card">
    <?php if($post->cover_url): ?><img class="card__img" src="<?php echo e($post->cover_url); ?>" alt="" loading="lazy"><?php endif; ?>
    <h3><a href="<?php echo e(route('posts.show', $post->slug)); ?>"><?php echo e($post->title); ?></a></h3>
    <p class="meta"><?php echo e($post->published_at?->translatedFormat('j F Y')); ?></p>
    <p><?php echo e($post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->body), 120)); ?></p>
</article>
<?php /**PATH C:\Users\Gigabyte\Documents\F's Projects\booking-umkm\resources\views/site/_post_card.blade.php ENDPATH**/ ?>