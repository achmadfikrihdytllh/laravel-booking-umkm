@extends('layouts.site')
@section('title', $post->title.' - '.setting('business_name'))
@section('description', $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->body), 150))

@section('content')
<article class="section">
<div class="wrap narrow">
    <p><a href="{{ route('posts.index') }}">&larr; {{ __('site.nav_news') }}</a></p>
    <h1>{{ $post->title }}</h1>
    <p class="meta">{{ $post->published_at?->translatedFormat('j F Y') }}</p>
    @if ($post->cover_url)<img class="cover" src="{{ $post->cover_url }}" alt="">@endif
    <div class="prose">{!! nl2br(e($post->body)) !!}</div>
</div>
</article>
@endsection
