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
                    <div class="row align-items-center justify-content-between">
                        <!-- Ikon dan Info -->
                        <div class="col-md-4 d-flex align-items-center mb-2 mb-md-0">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md me-3">
                                <i class="fa fa-shopping-cart text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Daftar Customer</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ $totalCustomers }}
                                    <span class="text-success text-sm font-weight-bolder">Terdaftar</span>
                                </h5>
                            </div>
                        </div>

                        <!-- Search -->
                        <div class="col-md-4 d-flex justify-content-center mb-2 mb-md-0">
                            <div class="input-group input-group-sm" style="max-width: 250px;">
                                <input type="text" class="form-control" id="searchCustomer"
                                    placeholder="Cari customer..." name="search">
                                <span class="input-group-text">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </span>
                            </div>
                        </div>

                        <!-- Filter -->
                        <div class="col-md-4 text-end">
                            <div class="dropdown">
                                <a class="icon icon-shape bg-gradient-secondary shadow text-center border-radius-md"
                                    href="#" role="button" id="filterDropdown" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fas fa-filter text-lg opacity-10" aria-hidden="true"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end position-absolute top-10"
                                    aria-labelledby="filterDropdown">
                                    <li><a class="dropdown-item" href="javascript:void(0)"
                                            onclick="filterBy('id')">Filter by ID</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0)"
                                            onclick="filterBy('name')">Filter by Nama</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-n2 p-4" id="customer-container">
        @include('components.card.customer-order-card')
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropdowns = document.querySelectorAll('.dropdown-menu');
        dropdowns.forEach(menu => {
        const style = document.createElement('style');
        style.innerHTML = `
            .dropdown-menu::before {
            display: none !important;
            }
        `;
        document.head.appendChild(style);
        });
    });

    let searchTimeout;

    document.getElementById('searchCustomer').addEventListener('input', function () {
        clearTimeout(searchTimeout);

        const keyword = this.value;
        const container = document.getElementById('customer-container');

        searchTimeout = setTimeout(() => {
        container.innerHTML = `
            <div class="w-100 text-center my-5">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2 text-sm text-secondary">Memuat data...</p>
            </div>
            `;

        fetch(`{{ route('order.index') }}?search=${encodeURIComponent(keyword)}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(html => {
            container.innerHTML = html;
        });
        }, 300); // debounce 300ms
    });

    function filterBy(column) {
        const container = document.getElementById('customer-container');
        
        // Tampilkan loading sebelum request AJAX
        container.innerHTML = `
            <div class="w-100 text-center my-5">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2 text-sm text-secondary">Memuat data...</p>
            </div>
            `;

        fetch(`{{ route('order.index') }}?sort_by=${column}`, {
            headers: {
            'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.text())
        .then(html => {
            container.innerHTML = html;
        });
    }
</script>

@endsection