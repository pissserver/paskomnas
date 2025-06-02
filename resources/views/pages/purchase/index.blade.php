@extends('layouts.user_type.auth')

@section('title', 'Purchasing Paskomnas || PISS')

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
        <div class="col-12">
            <div class="card py-3">
                <div class="nav-wrapper position-relative d-flex justify-content-center pt-1">
                    <ul class="nav nav-pills bg-transparent" role="tablist">
                        <!-- Tab: Purchasing All -->
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active mb-2 me-2" id="purchasing-all-tab" data-bs-toggle="tab"
                                href="#purchasing-all" role="tab" aria-controls="purchasing-all" aria-selected="true">
                                <i class="fas fa-shopping-bag me-1 text-primary"></i> Purchasing All
                            </a>
                        </li>

                        <!-- Tab: Purchasing per Customer -->
                        <li class="nav-item" role="presentation">
                            <a class="nav-link mb-2" id="purchasing-customer-tab" data-bs-toggle="tab"
                                href="#purchasing-customer" role="tab" aria-controls="purchasing-customer"
                                aria-selected="false">
                                <i class="fas fa-users me-1 text-primary"></i> Purchasing per Customer
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row order-process mt-5">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div class="d-flex align-items-center bg-gradient-secondary ps-3 pe-3 rounded-2 text-white">
                            <i class="fa fa-shopping-bag me-2"></i>
                            <h6 class="mb-0 text-white">Purchasing All</h6>
                        </div>
                        <div class="d-flex align-items-center">
                            <input type="text" class="form-control form-control-sm me-1" id="searchOrderProcess"
                                placeholder="Cari order..." name="search" style="max-width: 200px;">
                            <span class="input-group-text" onclick="togglePassword('password_confirmation', this)">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-4 pb-2">
                    <div class="table-responsive p-0">
                        {{-- @if ($orderProcess->isEmpty())
                        <div class="empty-data">
                            <div class="text-center">
                                <img src="{{ asset('assets/img/empty-data.png') }}" alt="No Data" class="img-fluid"
                        width="450">
                        <h4 class="text-secondary">Belum ada order diproses</h4>
                    </div>
                </div>
                @else --}}
                <table class="table table-responsive align-items-center mb-0 table-hover table-striped">
                    <thead>
                        <tr>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                No
                            </th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Nama Komoditi
                            </th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Satuan
                            </th>
                            @foreach ($customers as $customer)
                            <th class="text-center text-uppercase text-primary text-xxs font-weight-bolder opacity-7">
                                {{ $customer->name }}
                            </th>
                            @endforeach
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Total
                            </th>
                        </tr>
                    </thead>
                    <tbody id="orderProcessTableBody">
                        @foreach ($goods as $index => $good)
                        <tr>
                            <td class="text-center">
                                <p class="text-2xl mb-0">{{ $index + 1 }}</p>
                            </td>
                            <td class="text-center">
                                <p class="text-2xl mb-0 text-capitalize">{{ $good->name }}</p>
                            </td>
                            <td class="text-center">
                                <p class="text-2xl mb-0">{{ $good->unit }}</p>
                            </td>
                            @foreach ($customers as $customer)
                            <td class="text-center">

                            </td>
                            @endforeach
                            <td class="text-center">

                            </td>
                            @endforeach
                    </tbody>
                </table>
                {{-- @endif --}}
            </div>
        </div>

        <div class="d-flex justify-content-center mt-3 mb-2" id="pagination">

        </div>
    </div>
</div>
</div>
</div>

@endsection