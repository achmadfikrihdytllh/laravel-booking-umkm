@extends('layouts.admin')
@section('title', __('admin.nav_posts'))
@section('content')
<h1>{{ $post->exists ? __('admin.edit') : __('admin.add') }} {{ __('admin.nav_posts') }}</h1>
<form method="post" enctype="multipart/form-data" class="form panel" action="{{ $post->exists ? route('admin.posts.update', $post) : route('admin.posts.store') }}">
    @csrf @if ($post->exists) @method('PUT') @endif
    <label>{{ __('admin.post_title') }}<input name="title" required maxlength="160" value="{{ old('title', $post->title) }}"></label>
    <label>{{ __('admin.excerpt') }}<textarea name="excerpt" rows="2" maxlength="300">{{ old('excerpt', $post->excerpt) }}</textarea></label>
    <label>{{ __('admin.body') }}<textarea name="body" rows="12" required>{{ old('body', $post->body) }}</textarea></label>
    <label>{{ __('admin.cover') }}<input type="file" name="cover" accept="image/*"></label>
    @if ($post->cover_url)<img src="{{ $post->cover_url }}" alt="" width="160">@endif
    <label>{{ __('admin.status') }}<select name="status"><option value="draft" @selected(old('status', $post->status) === 'draft')>{{ __('admin.draft') }}</option><option value="published" @selected(old('status', $post->status) === 'published')>{{ __('admin.published') }}</option></select></label>
    <div class="row"><button class="btn" type="submit">{{ __('admin.save') }}</button><a href="{{ route('admin.posts.index') }}">{{ __('admin.cancel') }}</a></div>
</form>
@endsection
