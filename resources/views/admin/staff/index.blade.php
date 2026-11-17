@extends('layouts.admin')
@section('title', __('admin.nav_staff'))
@section('content')
<div class="row row--between"><h1>{{ __('admin.nav_staff') }} ({{ staff_label() }})</h1><a class="btn btn--sm" href="{{ route('admin.staff.create') }}">+ {{ __('admin.add') }}</a></div>
<div class="tablewrap"><table>
<thead><tr><th>{{ __('admin.name') }}</th><th>{{ __('admin.title_role') }}</th><th>{{ __('admin.serves') }}</th><th>{{ __('admin.status') }}</th><th></th></tr></thead>
<tbody>
@forelse ($staffList as $p)
<tr><td>{{ $p->name }}</td><td>{{ $p->title }}</td><td>{{ $p->services->pluck('name')->join(', ') }}</td><td>{{ $p->is_active ? __('admin.active') : __('admin.inactive') }}</td>
<td class="row"><a href="{{ route('admin.staff.edit', $p) }}">{{ __('admin.edit') }}</a>
<form class="inline" method="post" action="{{ route('admin.staff.destroy', $p) }}" onsubmit="return confirm('{{ __('admin.delete_confirm') }}')">@csrf @method('DELETE')<button class="btn btn--sm btn--danger" type="submit">{{ __('admin.delete') }}</button></form></td></tr>
@empty<tr><td colspan="5" class="muted">{{ __('admin.none') }}</td></tr>@endforelse
</tbody></table></div>
@endsection
