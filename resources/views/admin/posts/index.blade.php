@extends('layouts.admin')
@section('title', __('admin.nav_posts'))
@section('content')
<div class="row row--between"><h1>{{ __('admin.nav_posts') }}</h1><a class="btn btn--sm" href="{{ route('admin.posts.create') }}">+ {{ __('admin.add') }}</a></div>
<div class="tablewrap"><table>
<thead><tr><th>{{ __('admin.post_title') }}</th><th>{{ __('admin.status') }}</th><th>{{ __('admin.updated') }}</th><th></th></tr></thead>
<tbody>
@forelse ($posts as $p)
<tr><td>{{ $p->title }}</td><td>{{ $p->status === 'published' ? __('admin.published') : __('admin.draft') }}</td><td>{{ $p->updated_at->format('d/m/Y H:i') }}</td>
<td class="row"><a href="{{ route('admin.posts.edit', $p) }}">{{ __('admin.edit') }}</a>
<form class="inline" method="post" action="{{ route('admin.posts.destroy', $p) }}" onsubmit="return confirm('{{ __('admin.delete_confirm') }}')">@csrf @method('DELETE')<button class="btn btn--sm btn--danger" type="submit">{{ __('admin.delete') }}</button></form></td></tr>
@empty<tr><td colspan="4" class="muted">{{ __('admin.none') }}</td></tr>@endforelse
</tbody></table></div>
<div class="pager">{{ $posts->links('pagination::simple-default') }}</div>
@endsection
