@extends('layouts.site')

@section('content')
<section class="hero">
    <div class="wrap hero__in">
        <div>
            <h1>{{ setting('business_name') }}</h1>
            <p class="lead">{{ setting('tagline') }}</p>
            <div class="row">
                <a class="btn" href="#reservasi">{{ __('site.hero_cta') }}</a>
                @if ($wa = wa_link(setting('whatsapp'), setting('wa_greeting')))
                    <a class="btn btn--ghost" href="{{ $wa }}" target="_blank" rel="noopener">{{ __('site.hero_secondary') }}</a>
                @endif
            </div>
        </div>
        @if (public_url(setting('logo_path')))
            <img class="hero__logo" src="{{ public_url(setting('logo_path')) }}" alt="">
        @endif
    </div>
</section>

@if (setting('about'))
<section class="section" id="tentang">
    <div class="wrap narrow">
        <h2>{{ __('site.about_title') }}</h2>
        <p class="prose">{!! nl2br(e(setting('about'))) !!}</p>
    </div>
</section>
@endif

<section class="section section--alt" id="layanan">
    <div class="wrap">
        <h2>{{ __('site.services_title') }}</h2>
        <p class="muted">{{ __('site.services_sub') }}</p>
        <div class="grid">
            @forelse ($services as $s)
                <article class="card">
                    <h3>{{ $s->name }}</h3>
                    @if ($s->description)<p>{{ $s->description }}</p>@endif
                    <p class="meta">{{ __('site.minutes', ['n' => $s->duration_minutes]) }}@if ($s->price_label) &middot; {{ $s->price_label }}@endif</p>
                </article>
            @empty
                <p class="muted">{{ __('site.no_services') }}</p>
            @endforelse
        </div>
    </div>
</section>

@if ($staff->isNotEmpty())
<section class="section" id="tim">
    <div class="wrap">
        <h2>{{ __('site.team_title') }}</h2>
        <div class="grid grid--people">
            @foreach ($staff as $p)
                <article class="person">
                    @if ($p->photo_url)
                        <img src="{{ $p->photo_url }}" alt="{{ $p->name }}" loading="lazy">
                    @else
                        <div class="person__ph" aria-hidden="true">{{ mb_strtoupper(mb_substr($p->name, 0, 1)) }}</div>
                    @endif
                    <h3>{{ $p->name }}</h3>
                    @if ($p->title)<p class="meta">{{ $p->title }}</p>@endif
                    @if ($p->bio)<p>{{ $p->bio }}</p>@endif
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="section section--alt" id="reservasi">
    <div class="wrap narrow">
        <h2>{{ __('booking.title') }}</h2>
        <p class="muted">{{ __('booking.subtitle') }}</p>
        @include('partials.flash')
        @include('site._form')
    </div>
</section>

@if ($posts->isNotEmpty())
<section class="section" id="berita">
    <div class="wrap">
        <div class="row row--between">
            <h2>{{ __('site.news_title') }}</h2>
            <a href="{{ route('posts.index') }}">{{ __('site.news_all') }} &rarr;</a>
        </div>
        <div class="grid">
            @foreach ($posts as $p)
                @include('site._post_card', ['post' => $p])
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="section section--alt" id="kontak">
    <div class="wrap">
        <h2>{{ __('site.contact_title') }}</h2>
        <div class="grid grid--2">
            <div>
                @if (setting('address'))<p><strong>{{ __('site.address') }}</strong><br>{!! nl2br(e(setting('address'))) !!}</p>@endif
                @if (setting('opening_hours'))<p><strong>{{ __('site.hours') }}</strong><br>{!! nl2br(e(setting('opening_hours'))) !!}</p>@endif
                @if (setting('maps_url'))<p><a href="{{ setting('maps_url') }}" target="_blank" rel="noopener">{{ __('site.open_map') }} &rarr;</a></p>@endif
            </div>
            <div>
                @if (setting('phone'))<p>Tel: <a href="tel:{{ preg_replace('/[^0-9+]/', '', setting('phone')) }}">{{ setting('phone') }}</a></p>@endif
                @if (setting('email'))<p>Email: <a href="mailto:{{ setting('email') }}">{{ setting('email') }}</a></p>@endif
                @if (setting('instagram'))<p>Instagram: <a href="https://instagram.com/{{ ltrim(setting('instagram'), '@') }}" target="_blank" rel="noopener">&#64;{{ ltrim(setting('instagram'), '@') }}</a></p>@endif
                @if ($wa)<p><a class="btn" href="{{ $wa }}" target="_blank" rel="noopener">{{ __('site.chat_wa') }}</a></p>@endif
            </div>
        </div>
    </div>
</section>
@endsection
