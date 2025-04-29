@extends('layouts.app')

@section('auth')
<div class="container-fluid d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="text-center">
        <img src="{{ asset('assets/img/403-image.png') }}" alt="" width="300" class="img-fluid pb-4">
        <h1>403 Access Denied</h1>
        <p>You do not have permission to access this page.</p>
        <a href="{{ url('/') }}" class="btn btn-primary mt-3">Back to dashboard</a>
    </div>
</div>
@endsection