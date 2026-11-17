<article class="card">
    @if ($post->cover_url)<img class="card__img" src="{{ $post->cover_url }}" alt="" loading="lazy">@endif
    <h3><a href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a></h3>
    <p class="meta">{{ $post->published_at?->translatedFormat('j F Y') }}</p>
    <p>{{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->body), 120) }}</p>
</article>
