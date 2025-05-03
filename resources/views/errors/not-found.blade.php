@extends('layouts.app')

@section('auth')
<div class="container-fluid d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="text-center">
        <img src="{{ asset('assets/img/404-image.png') }}" alt="" width="300" class="img-fluid">
        <h1>404 Not Found</h1>
        <p>The page you are looking for does not exist.</p>
        <a href="{{ route('dashboard') }}" class="btn btn-primary mt-3">Back to dashboard</a>
    </div>
</div>
@endsection

@section('guest')
<div class="container-fluid d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="text-center">
        <img src="{{ asset('assets/img/404-image.png') }}" alt="" width="300" class="img-fluid">
        <h1>404 Not Found</h1>
        <p>The page you are looking for does not exist.</p>
        <a href="{{ route('login') }}" class="btn btn-primary mt-3">Login</a>
    </div>
</div>
@endsection