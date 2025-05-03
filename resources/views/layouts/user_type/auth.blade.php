@extends('layouts.app')

@section('auth')

@if (Auth::user()->role === 'master')
@if (\Request::is('virtual-reality'))
{{-- @include('layouts.navbars.auth.nav') --}}
<div class="border-radius-xl my-3 mx-3 position-relative" style="background-image: url('{{ asset('assets/img/vr-bg.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            height: 94vh;
            overflow: hidden;">
    {{-- @include('layouts.navbars.auth.master.sidebar') --}}
    <main class="main-content mt-1 border-radius-lg">
        @yield('content')
    </main>
</div>
{{-- @include('layouts.footers.auth.footer') --}}

@else
@include('layouts.navbars.auth.master.sidebar')
<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
    @include('layouts.navbars.auth.nav')
    <div class="container-fluid py-4">
        @yield('content')
        @include('layouts.footers.auth.footer')
    </div>
</main>
@include('components.fixed-plugin')
@endif

@elseif (Auth::user()->role === 'user')
@if (\Request::is('virtual-reality'))
{{-- @include('layouts.navbars.auth.nav') --}}
<div class="border-radius-xl my-3 mx-3 position-relative" style="background-image: url('{{ asset('assets/img/vr-bg.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            height: 94vh;
            overflow: hidden;">

    {{-- Sidebar jika perlu --}}
    {{-- @include('layouts.navbars.auth.user.sidebar') --}}

    <main class="main-content border-radius-lg w-100">
        @yield('content')
    </main>
</div>

{{-- @include('layouts.footers.auth.footer') --}}

@else
@include('layouts.navbars.auth.user.sidebar')
<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
    @include('layouts.navbars.auth.nav')
    <div class="container-fluid py-4">
        @yield('content')
        @include('layouts.footers.auth.footer')
    </div>
</main>
@include('components.fixed-plugin')
@endif


@endif


@endsection