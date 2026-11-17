@extends('layouts.admin')
@section('title', __('admin.nav_services'))
@section('content')
<h1>{{ $service->exists ? __('admin.edit') : __('admin.add') }} {{ __('admin.nav_services') }}</h1>
<form method="post" class="form panel" action="{{ $service->exists ? route('admin.services.update', $service) : route('admin.services.store') }}">
    @csrf @if ($service->exists) @method('PUT') @endif
    <label>{{ __('admin.name') }}<input name="name" required maxlength="120" value="{{ old('name', $service->name) }}"></label>
    <label>{{ __('admin.description') }}<textarea name="description" rows="3">{{ old('description', $service->description) }}</textarea></label>
    <div class="cols">
        <label>{{ __('admin.duration') }}<input type="number" name="duration_minutes" min="5" max="600" required value="{{ old('duration_minutes', $service->duration_minutes) }}"></label>
        <label>{{ __('admin.price_label') }}<input name="price_label" maxlength="60" value="{{ old('price_label', $service->price_label) }}"></label>
        <label>{{ __('admin.sort') }}<input type="number" name="sort_order" min="0" value="{{ old('sort_order', $service->sort_order ?? 0) }}"></label>
    </div>
    <p class="muted small">{{ __('admin.price_hint') }}</p>
    <fieldset><legend>{{ __('admin.staff_for_service') }}</legend>
        @php($chosen = old('staff', $service->exists ? $service->staff->pluck('id')->all() : []))
        @foreach ($staff as $p)<label class="chk"><input type="checkbox" name="staff[]" value="{{ $p->id }}" @checked(in_array($p->id, $chosen))> {{ $p->name }}</label>@endforeach
    </fieldset>
    <label class="chk"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $service->is_active))> {{ __('admin.active') }}</label>
    <div class="row"><button class="btn" type="submit">{{ __('admin.save') }}</button><a href="{{ route('admin.services.index') }}">{{ __('admin.cancel') }}</a></div>
</form>
@endsection
