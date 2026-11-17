@extends('layouts.site')
@section('title', __('booking.detail_title').' '.$booking->code)

@section('content')
<section class="section">
<div class="wrap narrow">
    @if ($justCreated)
        <div class="alert alert--ok"><strong>{{ __('booking.created_title') }}</strong><br>{{ __('booking.created_text') }}</div>
    @endif
    @if (session('cancelled'))
        <div class="alert alert--ok">{{ __('booking.cancelled_notice') }}</div>
    @endif

    <div class="ticket">
        <p class="muted">{{ __('booking.code') }}</p>
        <p class="ticket__code">{{ $booking->code }}</p>
        <span class="badge badge--{{ $booking->status }}">{{ __('booking.status.'.$booking->status) }}</span>

        <dl class="dl">
            <dt>{{ __('booking.service') }}</dt><dd>{{ $booking->service->name }}</dd>
            <dt>{{ staff_label() }}</dt><dd>{{ $booking->staff->name }}</dd>
            <dt>{{ __('booking.when') }}</dt><dd>{{ $booking->starts_at->translatedFormat('l, j F Y') }} &middot; {{ $booking->starts_at->format('H:i') }}</dd>
            @if ($verified)
                <dt>{{ __('booking.customer') }}</dt><dd>{{ $booking->customer_name }}</dd>
            @endif
        </dl>

        @if (! $verified)
            <p class="muted">{{ __('booking.locked') }}</p>
            <a class="btn btn--ghost" href="{{ route('booking.lookup', ['code' => $booking->code]) }}">{{ __('booking.check_btn') }}</a>
        @else
            <div class="row">
                @if ($wa = wa_link(setting('whatsapp'), __('booking.wa_message', ['name' => $booking->customer_name, 'service' => $booking->service->name, 'when' => $booking->starts_at->format('d/m/Y H:i'), 'code' => $booking->code])))
                    <a class="btn" href="{{ $wa }}" target="_blank" rel="noopener">{{ __('booking.confirm_wa') }}</a>
                @endif
                @if ($booking->isActive() && $booking->starts_at->isFuture())
                    <form method="post" action="{{ route('booking.cancel', $booking->code) }}" onsubmit="return confirm('{{ __('booking.cancel_confirm') }}')">
                        @csrf
                        <button class="btn btn--ghost" type="submit">{{ __('booking.cancel') }}</button>
                    </form>
                @endif
            </div>
        @endif
    </div>
    <p><a href="{{ route('home') }}">&larr; {{ __('site.back') }}</a></p>
</div>
</section>
@endsection
