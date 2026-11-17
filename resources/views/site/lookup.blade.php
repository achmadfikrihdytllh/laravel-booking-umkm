@extends('layouts.site')
@section('title', __('booking.check_title'))

@section('content')
<section class="section">
<div class="wrap narrow">
    <h1>{{ __('booking.check_title') }}</h1>
    <p class="muted">{{ __('booking.check_text') }}</p>
    @if ($searched)<div class="alert alert--err">{{ __('booking.not_found') }}</div>@endif
    <form method="get" action="{{ route('booking.lookup') }}" class="form">
        <label>{{ __('booking.code') }}<input type="text" name="code" value="{{ request('code') }}" required maxlength="12" autocapitalize="characters"></label>
        <label>{{ __('booking.phone') }}<input type="tel" name="phone" value="{{ request('phone') }}" required inputmode="tel"></label>
        <button class="btn" type="submit">{{ __('booking.check_btn') }}</button>
    </form>
</div>
</section>
@endsection
