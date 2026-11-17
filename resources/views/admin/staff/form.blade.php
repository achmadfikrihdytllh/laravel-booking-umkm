@extends('layouts.admin')
@section('title', __('admin.nav_staff'))
@section('content')
<h1>{{ $staff->exists ? __('admin.edit') : __('admin.add') }} {{ staff_label() }}</h1>
<form method="post" enctype="multipart/form-data" class="form panel" action="{{ $staff->exists ? route('admin.staff.update', $staff) : route('admin.staff.store') }}">
    @csrf @if ($staff->exists) @method('PUT') @endif
    <div class="cols">
        <label>{{ __('admin.name') }}<input name="name" required maxlength="120" value="{{ old('name', $staff->name) }}"></label>
        <label>{{ __('admin.title_role') }}<input name="title" maxlength="120" value="{{ old('title', $staff->title) }}"></label>
        <label>{{ __('admin.sort') }}<input type="number" name="sort_order" min="0" value="{{ old('sort_order', $staff->sort_order ?? 0) }}"></label>
    </div>
    <label>{{ __('admin.bio') }}<textarea name="bio" rows="3">{{ old('bio', $staff->bio) }}</textarea></label>
    <label>{{ __('admin.photo') }}<input type="file" name="photo" accept="image/*"></label>
    @if ($staff->photo_url)<img src="{{ $staff->photo_url }}" alt="" width="80">@endif
    <fieldset><legend>{{ __('admin.serves') }}</legend>
        @php($chosen = old('services', $staff->exists ? $staff->services->pluck('id')->all() : []))
        @foreach ($services as $s)<label class="chk"><input type="checkbox" name="services[]" value="{{ $s->id }}" @checked(in_array($s->id, $chosen))> {{ $s->name }}</label>@endforeach
    </fieldset>
    <label class="chk"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $staff->is_active))> {{ __('admin.active') }}</label>
    <div class="row"><button class="btn" type="submit">{{ __('admin.save') }}</button><a href="{{ route('admin.staff.index') }}">{{ __('admin.cancel') }}</a></div>
</form>

<h2 style="margin-top:32px">{{ __('admin.weekly_schedule') }}</h2>
@if (! $staff->exists)
    <p class="muted">{{ __('admin.save_first') }}</p>
@else
<form method="post" action="{{ route('admin.staff.schedule', $staff) }}" class="panel" id="sched">
    @csrf @method('PUT')
    <p class="muted small">{{ __('admin.schedule_hint') }}</p>
    <div class="sched">
    @foreach (__('admin.days') as $day => $label)
        <div class="sched__day" data-day="{{ $day }}">
            <strong>{{ $label }}</strong>
            <div class="sched__ranges">
                @foreach ($staff->schedules->where('weekday', $day)->values() as $i => $r)
                    <div class="range"><input type="time" name="schedule[{{ $day }}][{{ $i }}][start]" value="{{ $r->start_time }}"> &ndash; <input type="time" name="schedule[{{ $day }}][{{ $i }}][end]" value="{{ $r->end_time }}"></div>
                @endforeach
                <button type="button" class="btn btn--sm btn--ghost add-range" style="width:fit-content">{{ __('admin.add_range') }}</button>
            </div>
        </div>
    @endforeach
    </div>
    <p><button class="btn" type="submit">{{ __('admin.save_schedule') }}</button></p>
</form>
<script>
document.querySelectorAll('#sched .add-range').forEach(function (b) {
  b.addEventListener('click', function () {
    var wrap = b.parentElement, day = wrap.parentElement.dataset.day, n = wrap.querySelectorAll('.range').length;
    var d = document.createElement('div'); d.className = 'range';
    d.innerHTML = '<input type="time" name="schedule[' + day + '][' + n + '][start]" value="09:00"> &ndash; <input type="time" name="schedule[' + day + '][' + n + '][end]" value="17:00">';
    wrap.insertBefore(d, b);
  });
});
</script>
@endif
@endsection
