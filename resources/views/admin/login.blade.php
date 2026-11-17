<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>@include('partials.head')<title>{{ __('admin.login') }} - {{ setting('business_name') }}</title><meta name="robots" content="noindex"></head>
<body>
<div class="login">
    <h1>{{ __('admin.login_title') }}</h1>
    @include('partials.flash')
    <form method="post" action="{{ route('admin.login.post') }}" class="form">
        @csrf
        <label>Email<input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"></label>
        <label>{{ __('admin.password') }}<input type="password" name="password" required autocomplete="current-password"></label>
        <label class="chk"><input type="checkbox" name="remember" value="1"> {{ __('admin.remember') }}</label>
        <button class="btn" type="submit">{{ __('admin.login') }}</button>
    </form>
    <p><a href="{{ route('home') }}">&larr; {{ __('site.back') }}</a></p>
</div>
</body>
</html>
