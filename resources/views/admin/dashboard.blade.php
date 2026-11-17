@extends('layouts.admin')
@section('title', __('admin.nav_dashboard'))
@section('content')
<h1>{{ __('admin.nav_dashboard') }}</h1>
<div class="stats">
    <div class="stat"><b>{{ $todayCount }}</b>{{ __('admin.today') }}</div>
    <div class="stat"><b>{{ $pendingCount }}</b>{{ __('admin.pending') }}</div>
    <div class="stat"><b>{{ $weekCount }}</b>{{ __('admin.this_week') }}</div>
    <div class="stat"><b>{{ $monthCount }}</b>{{ __('admin.this_month') }}</div>
</div>
<h2>{{ __('admin.upcoming') }}</h2>
@include('admin.bookings._table', ['bookings' => $upcoming])
@endsection
