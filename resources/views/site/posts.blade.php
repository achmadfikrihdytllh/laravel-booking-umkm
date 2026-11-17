@extends('layouts.site')
@section('title', __('site.news_title'))

@section('content')
<section class="section">
<div class="wrap">
    <h1>{{ __('site.news_title') }}</h1>
    <div class="grid">
        @forelse ($posts as $p)
            @include('site._post_card', ['post' => $p])
        @empty
            <p class="muted">{{ __('site.news_empty') }}</p>
        @endforelse
    </div>
    <div class="pager">{{ $posts->links('pagination::simple-default') }}</div>
</div>
</section>
@endsection
