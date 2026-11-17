<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('partials.head')
    <title>@yield('title', setting('business_name'))</title>
    <meta name="description" content="@yield('description', setting('tagline'))">
</head>
<body>
<a class="skip" href="#main">{{ __('site.skip') }}</a>
<header class="topbar">
    <div class="wrap topbar__in">
        <a class="brand" href="{{ route('home') }}">
            @if (public_url(setting('logo_path')))
                <img src="{{ public_url(setting('logo_path')) }}" alt="" class="brand__logo">
            @endif
            <span class="brand__name">{{ setting('business_name') }}</span>
        </a>
        <nav class="nav" aria-label="Menu">
            <a href="{{ route('home') }}#layanan">{{ __('site.nav_services') }}</a>
            <a href="{{ route('home') }}#tim">{{ __('site.nav_team') }}</a>
            <a href="{{ route('posts.index') }}">{{ __('site.nav_news') }}</a>
            <a href="{{ route('home') }}#kontak">{{ __('site.nav_contact') }}</a>
            <a href="{{ route('booking.lookup') }}">{{ __('site.nav_check') }}</a>
        </nav>
        <div class="topbar__end">
            <div class="langs" aria-label="{{ __('site.language') }}">
                <a href="{{ route('locale', 'id') }}" @class(['on' => app()->getLocale() === 'id']) hreflang="id">ID</a>
                <a href="{{ route('locale', 'en') }}" @class(['on' => app()->getLocale() === 'en']) hreflang="en">EN</a>
            </div>
            <a class="btn btn--sm" href="{{ route('home') }}#reservasi">{{ __('site.nav_book') }}</a>
        </div>
    </div>
</header>

<main id="main">@yield('content')</main>

<footer class="foot">
    <div class="wrap">
        <p>&copy; {{ date('Y') }} {{ setting('business_name') }}. {{ __('site.footer_rights') }}</p>
        <p><a href="{{ route('admin.login') }}" class="muted">Admin</a></p>
    </div>
</footer>

@if ($wa = wa_link(setting('whatsapp'), setting('wa_greeting')))
<a class="wa-float" href="{{ $wa }}" target="_blank" rel="noopener" aria-label="{{ __('site.wa_float') }}">
    <svg viewBox="0 0 24 24" width="28" height="28" aria-hidden="true"><path fill="currentColor" d="M6.62 10.79a15.05 15.05 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.02-.24c1.12.37 2.33.57 3.57.57a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.25.2 2.45.57 3.57a1 1 0 0 1-.25 1.02l-2.2 2.2z"/></svg>
</a>
@endif
@stack('scripts')
</body>
</html>
