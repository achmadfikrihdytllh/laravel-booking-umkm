@extends('layouts.admin')
@section('title', __('admin.nav_timeoffs'))
@section('content')
<h1>{{ __('admin.nav_timeoffs') }}</h1>
<p class="muted">{{ __('admin.timeoff_hint') }}</p>
<form method="post" action="{{ route('admin.time-offs.store') }}" class="form panel">
    @csrf
    <div class="cols">
        <label>{{ __('admin.who') }}<select name="staff_id"><option value="">{{ __('admin.all_business') }}</option>@foreach ($staff as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach</select></label>
        <label>{{ __('admin.start_date') }}<input type="date" name="start_date" required value="{{ old('start_date') }}"></label>
        <label>{{ __('admin.end_date') }}<input type="date" name="end_date" required value="{{ old('end_date') }}"></label>
        <label>{{ __('admin.reason') }}<input name="reason" maxlength="120" value="{{ old('reason') }}"></label>
    </div>
    <div><button class="btn" type="submit">{{ __('admin.add') }}</button></div>
</form>
<div class="tablewrap" style="margin-top:20px"><table>
<thead><tr><th>{{ __('admin.who') }}</th><th>{{ __('admin.start_date') }}</th><th>{{ __('admin.end_date') }}</th><th>{{ __('admin.reason') }}</th><th></th></tr></thead>
<tbody>
@forelse ($timeOffs as $t)
<tr><td>{{ $t->staff?->name ?? __('admin.all_business') }}</td><td>{{ $t->start_date->format('d/m/Y') }}</td><td>{{ $t->end_date->format('d/m/Y') }}</td><td>{{ $t->reason }}</td>
<td><form class="inline" method="post" action="{{ route('admin.time-offs.destroy', $t) }}" onsubmit="return confirm('{{ __('admin.delete_confirm') }}')">@csrf @method('DELETE')<button class="btn btn--sm btn--danger" type="submit">{{ __('admin.delete') }}</button></form></td></tr>
@empty<tr><td colspan="5" class="muted">{{ __('admin.none') }}</td></tr>@endforelse
</tbody></table></div>
@endsection
