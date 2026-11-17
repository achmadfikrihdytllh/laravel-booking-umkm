@if (session('ok'))
<div class="alert alert--ok" role="status">{{ session('ok') }}</div>
@endif
@if ($errors->any())
<div class="alert alert--err" role="alert">
    <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif
