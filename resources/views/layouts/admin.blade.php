<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('partials.head')
    <title>@yield('title', 'Admin') - {{ setting('business_name') }}</title>
    <meta name="robots" content="noindex">
</head>
<body>
<div class="adm">
    <aside class="side">
        <a class="brand" href="{{ route('admin.dashboard') }}">
            @if (public_url(setting('logo_path')))<img class="brand__logo" src="{{ public_url(setting('logo_path')) }}" alt="">@endif
            <span>{{ setting('business_name') }}</span>
        </a>
        @php($nav = [
            ['admin.dashboard', 'nav_dashboard', 'admin.dashboard'],
            ['admin.bookings.index', 'nav_bookings', 'admin.bookings.*'],
            ['admin.services.index', 'nav_services', 'admin.services.*'],
            ['admin.staff.index', 'nav_staff', 'admin.staff.*'],
            ['admin.time-offs.index', 'nav_timeoffs', 'admin.time-offs.*'],
            ['admin.posts.index', 'nav_posts', 'admin.posts.*'],
            ['admin.settings.edit', 'nav_settings', 'admin.settings.*'],
        ])
        @foreach ($nav as [$route, $label, $pattern])
            <a class="item {{ request()->routeIs($pattern) ? 'on' : '' }}" href="{{ route($route) }}">{{ __('admin.'.$label) }}</a>
        @endforeach
        <a class="item" href="{{ route('home') }}" target="_blank">{{ __('admin.view_site') }}</a>
        <form method="post" action="{{ route('admin.logout') }}">@csrf<button class="btn btn--ghost btn--sm" type="submit">{{ __('admin.logout') }}</button></form>
    </aside>
    <div class="main">
        @include('partials.flash')
        @yield('content')
    </div>
</div>
</body>
</html>
