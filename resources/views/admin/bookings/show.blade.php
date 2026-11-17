@extends('layouts.admin')
@section('title', $booking->code)
@section('content')
<p><a href="{{ route('admin.bookings.index') }}">&larr; {{ __('admin.back') }}</a></p>
<h1>{{ $booking->code }} <span class="badge badge--{{ $booking->status }}">{{ __('booking.status.'.$booking->status) }}</span></h1>
<div class="panel">
<dl class="dl">
    <dt>{{ __('admin.customer') }}</dt><dd>{{ $booking->customer_name }}</dd>
    <dt>{{ __('admin.whatsapp') }}</dt><dd>{{ $booking->customer_phone }}
        @if ($wa = wa_link($booking->customer_phone, 'Halo '.$booking->customer_name.', terkait reservasi '.$booking->code))
            &middot; <a href="{{ $wa }}" target="_blank" rel="noopener">{{ __('admin.contact_customer') }}</a>@endif</dd>
    @if ($booking->customer_email)<dt>Email</dt><dd>{{ $booking->customer_email }}</dd>@endif
    <dt>{{ __('admin.service') }}</dt><dd>{{ $booking->service->name }}</dd>
    <dt>{{ staff_label() }}</dt><dd>{{ $booking->staff->name }}</dd>
    <dt>{{ __('admin.when') }}</dt><dd>{{ $booking->starts_at->format('d/m/Y H:i') }} - {{ $booking->ends_at->format('H:i') }}</dd>
    @if ($booking->notes)<dt>{{ __('booking.notes') }}</dt><dd>{{ $booking->notes }}</dd>@endif
</dl>
<form method="post" action="{{ route('admin.bookings.update', $booking) }}" class="form">
    @csrf @method('PATCH')
    <label>{{ __('admin.set_status') }}
        <select name="status">@foreach (config('booking.statuses') as $s)<option value="{{ $s }}" @selected($booking->status === $s)>{{ __('booking.status.'.$s) }}</option>@endforeach</select></label>
    <label>{{ __('admin.admin_notes') }}<textarea name="admin_notes" rows="3">{{ old('admin_notes', $booking->admin_notes) }}</textarea></label>
    <div><button class="btn" type="submit">{{ __('admin.save') }}</button></div>
</form>
</div>
@endsection
