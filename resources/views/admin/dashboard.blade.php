@extends('layouts.app')
@section('content')

@if(auth()->check())
  {{-- DEBUG: remove after verifying --}}
  <div class="p-2 small text-muted">
    Roles: {{ auth()->user()->getRoleNames()->join(', ') }}<br>
    Perms count: {{ auth()->user()->getAllPermissions()->count() }}
  </div>
@endif
<div class="container py-4">
    <h1>Admin Dashboard</h1>
    <p>Use can to restrict sections here.</p>
</div>
@endsection
