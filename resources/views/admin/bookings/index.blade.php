@extends('layouts.admin')
@section('title', __('admin.nav_bookings'))
@section('content')
<div class="row row--between"><h1>{{ __('admin.nav_bookings') }}</h1>
    <a class="btn btn--sm" href="{{ route('admin.bookings.export', request()->query()) }}">{{ __('admin.export_csv') }}</a></div>
<form method="get" class="filters">
    <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ __('admin.search_ph') }}">
    <select name="status"><option value="">{{ __('admin.all_status') }}</option>
        @foreach (config('booking.statuses') as $s)<option value="{{ $s }}" @selected(request('status') === $s)>{{ __('booking.status.'.$s) }}</option>@endforeach</select>
    <select name="staff_id"><option value="">{{ __('admin.all_staff') }}</option>
        @foreach ($staff as $p)<option value="{{ $p->id }}" @selected(request('staff_id') == $p->id)>{{ $p->name }}</option>@endforeach</select>
    <input type="date" name="from" value="{{ request('from') }}" aria-label="{{ __('admin.from') }}">
    <input type="date" name="to" value="{{ request('to') }}" aria-label="{{ __('admin.to') }}">
    <button class="btn btn--sm" type="submit">{{ __('admin.filter') }}</button>
    <a class="btn btn--sm btn--ghost" href="{{ route('admin.bookings.index') }}">{{ __('admin.reset') }}</a>
</form>
@include('admin.bookings._table')
<div class="pager">{{ $bookings->links('pagination::simple-default') }}</div>
@endsection
