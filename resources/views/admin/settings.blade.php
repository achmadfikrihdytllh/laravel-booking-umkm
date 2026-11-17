@extends('layouts.admin')
@section('title', __('admin.nav_settings'))
@section('content')
@php($v = fn ($k) => old($k, setting($k)))
<h1>{{ __('admin.nav_settings') }}</h1>
<form method="post" enctype="multipart/form-data" action="{{ route('admin.settings.update') }}" class="form">
    @csrf @method('PUT')
    <fieldset class="panel form"><legend>{{ __('admin.brand') }}</legend>
        <label>{{ __('admin.business_name') }}<input name="business_name" required maxlength="80" value="{{ $v('business_name') }}"></label>
        <label>{{ __('admin.tagline') }}<input name="tagline" maxlength="160" value="{{ $v('tagline') }}"></label>
        <label>{{ __('admin.about') }}<textarea name="about" rows="4">{{ $v('about') }}</textarea></label>
        <label>{{ __('admin.logo') }}<input type="file" name="logo" accept="image/*"></label>
        @if (public_url(setting('logo_path')))
            <div class="row"><img src="{{ public_url(setting('logo_path')) }}" alt="" height="56">
            <label class="chk"><input type="checkbox" name="remove_logo" value="1"> {{ __('admin.remove_logo') }}</label></div>
        @endif
        <label>{{ __('admin.brand_color') }}<input type="color" name="brand_color" value="{{ $v('brand_color') }}" style="height:44px;width:90px;padding:2px"></label>
    </fieldset>
    <fieldset class="panel form"><legend>{{ __('admin.contact') }}</legend>
        <div class="cols">
            <label>{{ __('admin.whatsapp') }}<input name="whatsapp" maxlength="20" value="{{ $v('whatsapp') }}" placeholder="0812xxxxxxx"></label>
            <label>{{ __('admin.phone') }}<input name="phone" maxlength="30" value="{{ $v('phone') }}"></label>
            <label>{{ __('admin.email') }}<input type="email" name="email" maxlength="120" value="{{ $v('email') }}"></label>
            <label>{{ __('admin.instagram') }}<input name="instagram" maxlength="60" value="{{ $v('instagram') }}"></label>
        </div>
        <label>{{ __('admin.wa_greeting') }}<input name="wa_greeting" maxlength="200" value="{{ $v('wa_greeting') }}"></label>
        <label>{{ __('admin.address') }}<textarea name="address" rows="2">{{ $v('address') }}</textarea></label>
        <label>{{ __('admin.maps_url') }}<input type="url" name="maps_url" maxlength="500" value="{{ $v('maps_url') }}"></label>
        <label>{{ __('admin.opening_hours') }}<textarea name="opening_hours" rows="2">{{ $v('opening_hours') }}</textarea></label>
    </fieldset>
    <fieldset class="panel form"><legend>{{ __('admin.labels') }}</legend>
        <p class="muted small">{{ __('admin.labels_hint') }}</p>
        <div class="cols">
            <label>{{ __('admin.staff_label_id') }}<input name="staff_label_id" required maxlength="30" value="{{ $v('staff_label_id') }}"></label>
            <label>{{ __('admin.staff_label_en') }}<input name="staff_label_en" required maxlength="30" value="{{ $v('staff_label_en') }}"></label>
            <label>{{ __('admin.service_label_id') }}<input name="service_label_id" required maxlength="30" value="{{ $v('service_label_id') }}"></label>
            <label>{{ __('admin.service_label_en') }}<input name="service_label_en" required maxlength="30" value="{{ $v('service_label_en') }}"></label>
        </div>
    </fieldset>
    <fieldset class="panel form"><legend>{{ __('admin.rules') }}</legend>
        <div class="cols">
            <label>{{ __('admin.slot_step') }}<select name="slot_step">@foreach ([10,15,20,30,45,60] as $n)<option value="{{ $n }}" @selected((int) $v('slot_step') === $n)>{{ $n }}</option>@endforeach</select></label>
            <label>{{ __('admin.min_notice_hours') }}<input type="number" name="min_notice_hours" min="0" max="168" value="{{ $v('min_notice_hours') }}"></label>
            <label>{{ __('admin.max_advance_days') }}<input type="number" name="max_advance_days" min="1" max="365" value="{{ $v('max_advance_days') }}"></label>
        </div>
        <label class="chk"><input type="checkbox" name="auto_confirm" value="1" @checked((int) setting('auto_confirm', 0) === 1)> {{ __('admin.auto_confirm') }}</label>
    </fieldset>
    <div><button class="btn" type="submit">{{ __('admin.save') }}</button></div>
</form>
@endsection
