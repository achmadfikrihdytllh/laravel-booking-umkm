@extends('layouts.admin')
@section('title', __('admin.nav_services'))
@section('content')
<div class="row row--between"><h1>{{ __('admin.nav_services') }}</h1><a class="btn btn--sm" href="{{ route('admin.services.create') }}">+ {{ __('admin.add') }}</a></div>
<div class="tablewrap"><table>
<thead><tr><th>{{ __('admin.name') }}</th><th>{{ __('admin.duration') }}</th><th>{{ __('admin.price_label') }}</th><th>{{ staff_label() }}</th><th>{{ __('admin.status') }}</th><th></th></tr></thead>
<tbody>
@forelse ($services as $s)
<tr><td>{{ $s->name }}</td><td>{{ $s->duration_minutes }}</td><td>{{ $s->price_label }}</td><td>{{ $s->staff->pluck('name')->join(', ') }}</td>
<td>{{ $s->is_active ? __('admin.active') : __('admin.inactive') }}</td>
<td class="row"><a href="{{ route('admin.services.edit', $s) }}">{{ __('admin.edit') }}</a>
<form class="inline" method="post" action="{{ route('admin.services.destroy', $s) }}" onsubmit="return confirm('{{ __('admin.delete_confirm') }}')">@csrf @method('DELETE')<button class="btn btn--sm btn--danger" type="submit">{{ __('admin.delete') }}</button></form></td></tr>
@empty<tr><td colspan="6" class="muted">{{ __('admin.none') }}</td></tr>@endforelse
</tbody></table></div>
@endsection
