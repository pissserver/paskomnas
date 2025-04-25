@extends('layouts.app')

@section('auth')

@if (Auth::user()->role === 'master')
@if (\Request::is('virtual-reality'))
@include('layouts.navbars.auth.nav')
<div class="border-radius-xl mt-3 mx-3 position-relative"
    style="background-image: url('../assets/img/vr-bg.jpg') ; background-size: cover;">
    @include('layouts.navbars.auth.master.sidebar')
    <main class="main-content mt-1 border-radius-lg">
        @yield('content')
    </main>
</div>
@include('layouts.footers.auth.footer')

@else
@include('layouts.navbars.auth.master.sidebar')
<main
    class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg {{ (Request::is('rtl') ? 'overflow-hidden' : '') }}">
    @include('layouts.navbars.auth.nav')
    <div class="container-fluid py-4">
        @yield('content')
        @include('layouts.footers.auth.footer')
    </div>
</main>
@endif

@include('components.fixed-plugin')
@elseif (Auth::user()->role === 'user')
@if (\Request::is('virtual-reality'))
@include('layouts.navbars.auth.nav')
<div class="border-radius-xl mt-3 mx-3 position-relative"
    style="background-image: url('../assets/img/vr-bg.jpg') ; background-size: cover;">
    @include('layouts.navbars.auth.user.sidebar')
    <main class="main-content mt-1 border-radius-lg">
        @yield('content')
    </main>
</div>
@include('layouts.footers.auth.footer')

@else
@include('layouts.navbars.auth.user.sidebar')
<main
    class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg {{ (Request::is('rtl') ? 'overflow-hidden' : '') }}">
    @include('layouts.navbars.auth.nav')
    <div class="container-fluid py-4">
        @yield('content')
        @include('layouts.footers.auth.footer')
    </div>
</main>
@endif
@endif


@endsection