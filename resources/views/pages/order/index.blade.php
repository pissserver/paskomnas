@extends('layouts.user_type.auth')

@section('title', 'Order Paskomnas || PISS')

@section('content')

<div>
    @if($errors->any())
    <div class="mx-4 alert alert-primary alert-dismissible fade show" role="alert">
        <span class="alert-text text-white">
            {{$errors->first()}}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <i class="fa fa-close" aria-hidden="true"></i>
        </button>
    </div>
    @endif
    @if(session('success'))
    <div class="mx-4 alert alert-success alert-dismissible fade show" id="alert-success" role="alert">
        <span class="alert-text text-white">
            {{ session('success') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <i class="fa fa-close" aria-hidden="true"></i>
        </button>
    </div>
    @endif

    <div class="row">
        <div class="col-xl-12 col-sm-12 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Daftar Customer</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ $totalCustomers }}
                                    <span class="text-success text-sm font-weight-bolder">Terdaftar</span>
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="fa fa-shopping-cart text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-n2 p-4">
        @foreach($customers as $customer)
        <div class="col-md-3 mt-4">
            <div class="card">
                <div class="card-header mx-4 p-3 text-center">
                    <img src="{{ $customer->photo ?? asset('assets/img/default-profile.jpg') }}"
                        alt="{{ $customer->name }}" class="avatar shadow-lg img-fluid"
                        style="width: 80px; height: 80px; object-fit: cover;">
                </div>
                <div class="card-body pt-0 p-3 text-center">
                    <h6 class="text-center mb-0">{{ $customer->name }}</h6>
                    <span class="text-xs">{{ $customer->address }}</span>
                    <hr class="horizontal dark my-3">
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('order.create', $customer->id) }}" class="btn  btn-primary">
                            <i class="fas fa-shopping-cart me-2"></i> Order
                        </a>
                        <a href="" class="btn  btn-info">
                            <i class="fas fa-info-circle me-2"></i> Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection