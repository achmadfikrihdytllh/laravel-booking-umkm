<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
@if (public_url(setting('logo_path')))
<link rel="icon" href="{{ public_url(setting('logo_path')) }}">
@endif
<link rel="stylesheet" href="{{ asset('css/app.css') }}?v=1">
<style>:root{--brand: {{ brand_color() }};--on-brand: {{ on_brand() }};}</style>
