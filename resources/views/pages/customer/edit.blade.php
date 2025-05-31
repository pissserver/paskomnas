@extends('layouts.user_type.auth')

@section('title', 'Detail Customer')

@section('content')


<div class="container-fluid">
    <div class="page-header min-height-300 border-radius-xl mt-4"
        style="background-image: url('{{ asset('assets/img/bg-customer.jpg') }}'); background-position-y: 50%;">
        <span class="mask bg-gradient-dark opacity-2"></span>
    </div>
    <div class="card card-body blur shadow-blur mx-4 mt-n6">
        <div class="row gx-4">
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative mt-2">
                    <img id="previewImage"
                        src="{{ $customer->photo ? asset($customer->photo) : asset('assets/img/default-profile.jpg') }}"
                        alt="Preview" class="w-100 border-radius-lg shadow-sm">
                </div>
            </div>
            <div class="col-auto my-auto text-center">
                <div class="h-100 d-flex flex-column align-items-start">
                    <p class="mb-0 font-weight-bold text-sm text-uppercase btn bg-gradient-primary btn-sm px-4">
                        {{ $customer->name }}
                    </p>
                    <span class="badge {{ $badgeClass }} mt-2 d-flex align-items-center px-3 py-1 rounded-pill">
                        <i class="fa fa-award me-1"></i> {{-- Ganti dengan icon lain kalau mau --}}
                        {{ strtoupper($level) }}
                    </span>
                </div>
            </div>

            <div class="col-lg-5 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                <div class="nav-wrapper position-relative end-0">
                    <ul class="nav nav-pills nav-fill p-1 bg-transparent" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link mb-0 px-0 py-1 active" id="overview-tab" data-bs-toggle="tab"
                                href="#overview" role="tab" aria-controls="overview" aria-selected="true">
                                <i class="fas fa-chart-bar me-1"></i>
                                <span class=" ms-1">{{ __('Overview') }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-0 px-0 py-1" id="price-tab" data-bs-toggle="tab" href="#price"
                                role="tab" aria-controls="price" aria-selected="true">
                                <i class="fas fa-dollar-sign me-1"></i>
                                <span class="ms-1">{{ __('Harga') }}</span>
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link mb-0 px-0 py-1 " id="order-tab" data-bs-toggle="tab" href="#order"
                                role="tab" aria-controls="order" aria-selected="true">
                                <i class="fas fa-shopping-cart me-1"></i>
                                <span class="ms-1">{{ __('Data Order') }}</span>
                        </a>
                        </li> --}}
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="tab-content">
    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
        <div class="container-fluid py-4">
            <form action="{{ route('customer.update', $customer->id) }}" method="POST" role="form text-left"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header pb-0 p-4 d-flex justify-content-between">
                        <h6 class="mb-0">{{ __('Data Customer') }}</h6>

                        <a href="{{ route('customer.index') }}"
                            class="btn bg-gradient-danger btn-md mb-0">{{ 'Kembali' }}&nbsp;&nbsp;<i
                                class="fa fa-arrow-right"></i></a>
                    </div>
                    <div class="card-body pt-4 p-4">
                        @if($errors->any())
                        <div class="alert alert-primary alert-dismissible fade show" role="alert">
                            <span class="alert-text text-white">
                                {{$errors->first()}}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                <i class="fa fa-close" aria-hidden="true"></i>
                            </button>
                        </div>
                        @endif
                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" id="alert-success" role="alert">
                            <span class="alert-text text-white">
                                {{ session('success') }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                <i class="fa fa-close" aria-hidden="true"></i>
                            </button>
                        </div>
                        @endif

                        {{-- Informasi Umum --}}
                        <div class="basic-information">
                            <h6 class="mb-3 p-2 bg-gradient-dark text-white rounded d-inline-flex align-items-center">
                                <i class="fas fa-info-circle me-2"></i> {{ __('Informasi Umum') }}
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Nama Perusahaan</label>
                                        <div class="@error('name')border border-danger rounded-3 @enderror">
                                            <input class="form-control" type="text" placeholder="Nama Perusahaan"
                                                id="name" name="name" value="{{ old('name', $customer->name) }}">
                                            @error('name')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Status Perusahaan</label>
                                        <div
                                            class="@error('status') border border-danger rounded-3 p-2 @enderror d-flex mt-1">
                                            <div class="form-check me-4">
                                                <input class="form-check-input" type="radio" name="company_status"
                                                    value="pusat" id="status-pusat"
                                                    {{ old('company_status', $customer->company_status) == 'pusat' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="status-pusat">Pusat</label>
                                            </div>
                                            <div class="form-check me-4">
                                                <input class="form-check-input" type="radio" name="status"
                                                    value="cabang" id="status-cabang"
                                                    {{ old('company_status', $customer->company_status) == 'cabang' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="status-cabang">Cabang</label>
                                            </div>
                                        </div>
                                        @error('status')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company-address">Alamat Kantor</label>
                                        <div class="@error('company_address') border border-danger rounded-3 @enderror">
                                            <textarea class="form-control" placeholder="Alamat Kantor"
                                                id="company-address" name="company_address"
                                                rows="3">{{ old('company_address', $customer->company_address) }}</textarea>
                                            @error('company_address')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mailing-address">Alamat Surat Menyurat</label>
                                        <div class="@error('mailing_address') border border-danger rounded-3 @enderror">
                                            <textarea class="form-control" placeholder="Alamat Surat Menyurat"
                                                id="mailing-address" name="mailing_address"
                                                rows="3">{{ old('mailing_address', $customer->mailing_address) }}</textarea>
                                            @error('mailing_address')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="no-telp">No. Telepon Kantor</label>
                                        <div class="@error('no_telp') border border-danger rounded-3 @enderror">
                                            <input class="form-control" type="number" placeholder="No. Telepon Kantor"
                                                id="no-telp" name="no_telp"
                                                value="{{ old('no_telp', $customer->no_telp) }}">
                                            @error('no_telp')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email Kantor</label>
                                        <div class="@error('email') border border-danger rounded-3 @enderror">
                                            <input class="form-control" type="email" placeholder="Email Kantor"
                                                id="email" name="email" value="{{ old('email', $customer->email) }}">
                                            @error('email')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Kategori</label>
                                        <div
                                            class="@error('category') border border-danger rounded-3 p-2 @enderror d-flex">
                                            <div class="form-check me-4">
                                                <input class="form-check-input" type="radio" name="category"
                                                    value="perseorangan" id="kategori-perseorangan"
                                                    {{ old('category', $customer->category) == 'perseorangan' ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="kategori-perseorangan">Perseorangan</label>
                                            </div>
                                            <div class="form-check me-4">
                                                <input class="form-check-input" type="radio" name="category"
                                                    value="badan_usaha" id="kategori-badan-usaha"
                                                    {{ old('category', $customer->category) == 'badan_usaha' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="kategori-badan-usaha">Badan
                                                    Usaha</label>
                                            </div>
                                        </div>
                                        @error('category')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Jenis Usaha</label>
                                        <div
                                            class="@error('business_type') border border-danger rounded-3 p-2 @enderror d-flex flex-wrap">
                                            <div class="form-check me-4">
                                                <input class="form-check-input" type="radio" name="business_type"
                                                    value="restoran" id="usaha-restoran"
                                                    {{ old('business_type', $customer->business_type) == 'restoran' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="usaha-restoran">Restoran / Rumah
                                                    Makan</label>
                                            </div>
                                            <div class="form-check me-4">
                                                <input class="form-check-input" type="radio" name="business_type"
                                                    value="katering" id="usaha-katering"
                                                    {{ old('business_type', $customer->business_type) == 'katering' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="usaha-katering">Katering</label>
                                            </div>
                                            <div class="form-check me-4">
                                                <input class="form-check-input" type="radio" name="business_type"
                                                    value="hotel" id="usaha-hotel"
                                                    {{ old('business_type', $customer->business_type) == 'hotel' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="usaha-hotel">Hotel</label>
                                            </div>
                                            <div class="form-check me-4">
                                                <input class="form-check-input" type="radio" name="business_type"
                                                    value="lainnya" id="usaha-lainnya"
                                                    {{ old('business_type', $customer->business_type) == 'lainnya' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="usaha-lainnya">Lainnya</label>
                                            </div>
                                        </div>
                                        @error('business_type')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="npwp">NPWP</label>
                                        <div class="@error('npwp')border border-danger rounded-3 @enderror">
                                            <input class="form-control" type="text" placeholder="NPWP" id="npwp"
                                                name="npwp" value="{{ old('npwp', $customer->npwp) }}">
                                            @error('npwp')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="reason">Alasan Menggunakan</label>
                                        <div class="@error('reason') border border-danger rounded-3 @enderror">
                                            <textarea class="form-control" placeholder="Alasan menggunakan layanan ini"
                                                id="reason" name="reason"
                                                rows="4">{{ old('reason', $customer->reason) }}</textarea>
                                            @error('reason')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Data Kontak --}}
                        <div class="data-contact mt-5">
                            <h6 class="mb-3 p-2 bg-gradient-dark text-white rounded d-inline-flex align-items-center">
                                <i class="fas fa-envelope me-2"></i> {{ __('Data Kontak') }}
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="owner-name" class="form-label">Nama Pemilik</label>
                                        <input type="text"
                                            class="form-control @error('owner_name') is-invalid @enderror"
                                            id="owner-name" name="owner_name"
                                            value="{{ old('owner_name', $customer->owner_name) }}"
                                            placeholder="Nama Pemilik">
                                        @error('owner_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="owner-email" class="form-label">Email</label>
                                        <input type="email"
                                            class="form-control @error('owner_email') is-invalid @enderror"
                                            id="owner-email" name="owner_email"
                                            value="{{ old('owner_email', $customer->owner_email) }}"
                                            placeholder="Email Pemilik">
                                        @error('owner_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="owner-phone" class="form-label">Telepon</label>
                                        <input type="number"
                                            class="form-control @error('owner_phone') is-invalid @enderror"
                                            id="owner-phone" name="owner_phone"
                                            value="{{ old('owner_phone', $customer->owner_phone) }}"
                                            placeholder="Telepon Pemilik">
                                        @error('owner_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="owner-whatsapp" class="form-label">WhatsApp</label>
                                        <input type="number"
                                            class="form-control @error('owner_whatsapp') is-invalid @enderror"
                                            id="owner-whatsapp" name="owner_whatsapp"
                                            value="{{ old('owner_whatsapp', $customer->owner_whatsapp) }}"
                                            placeholder="WhatsApp Pemilik">
                                        @error('owner_whatsapp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="purchasing-name" class="form-label">Nama Purchasing</label>
                                        <input type="text"
                                            class="form-control @error('purchasing_name') is-invalid @enderror"
                                            id="purchasing-name" name="purchasing_name"
                                            value="{{ old('purchasing_name', $customer->purchasing_name) }}"
                                            placeholder="Nama Penanggung Jawab Purchasing">
                                        @error('purchasing_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="purchasing-email" class="form-label">Email</label>
                                        <input type="email"
                                            class="form-control @error('purchasing_email') is-invalid @enderror"
                                            id="purchasing-email" name="purchasing_email"
                                            value="{{ old('purchasing_email', $customer->purchasing_email) }}"
                                            placeholder="Email Penanggung Jawab Purchasing">
                                        @error('purchasing_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="purchasing-phone" class="form-label">Telepon</label>
                                        <input type="number"
                                            class="form-control @error('purchasing_phone') is-invalid @enderror"
                                            id="purchasing-phone" name="purchasing_phone"
                                            value="{{ old('purchasing_phone', $customer->purchasing_phone) }}"
                                            placeholder="Telepon Penanggung Jawab">
                                        @error('purchasing_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="purchasing-whatsapp" class="form-label">WhatsApp</label>
                                        <input type="number"
                                            class="form-control @error('purchasing_whatsapp') is-invalid @enderror"
                                            id="purchasing-whatsapp" name="purchasing_whatsapp"
                                            value="{{ old('purchasing_whatsapp', $customer->purchasing_whatsapp) }}"
                                            placeholder="WhatsApp Penanggung Jawab Purchasing">
                                        @error('purchasing_whatsapp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="finance-name" class="form-label">Nama Keuangan</label>
                                        <input type="text"
                                            class="form-control @error('finance_name') is-invalid @enderror"
                                            id="finance-name" name="finance_name"
                                            value="{{ old('finance_name', $customer->finance_name) }}"
                                            placeholder="Nama Penanggung Jawab Keuangan">
                                        @error('finance_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="finance-email" class="form-label">Email</label>
                                        <input type="email"
                                            class="form-control @error('finance_email') is-invalid @enderror"
                                            id="finance-email" name="finance_email"
                                            value="{{ old('finance_email', $customer->finance_email) }}"
                                            placeholder="Email Penanggung Jawab Keuangan">
                                        @error('finance_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="finance-phone" class="form-label">Telepon</label>
                                        <input type="number"
                                            class="form-control @error('finance_phone') is-invalid @enderror"
                                            id="finance-phone" name="finance_phone"
                                            value="{{ old('finance_phone', $customer->finance_phone) }}"
                                            placeholder="Telepon Penanggung Jawab Keuangan">
                                        @error('finance_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="finance-whatsapp" class="form-label">WhatsApp</label>
                                        <input type="number"
                                            class="form-control @error('finance_whatsapp') is-invalid @enderror"
                                            id="finance-whatsapp" name="finance_whatsapp"
                                            value="{{ old('finance_whatsapp', $customer->finance_whatsapp) }}"
                                            placeholder="WhatsApp Penanggung Jawab Keuangan">
                                        @error('finance_whatsapp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Sistem Pembayaran --}}
                        <div class="payment-system mt-5">
                            <h6 class="mb-3 p-2 bg-gradient-dark text-white rounded d-inline-flex align-items-center">
                                <i class="fas fa-credit-card me-2"></i> {{ __('Sistem Pembayaran') }}
                            </h6>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label d-block">Metode Pembayaran</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="payment_method"
                                                id="payment-cod" value="COD"
                                                {{ old('payment_method', $customer->payment_method) == 'COD' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="payment-cod">COD / Cash</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="payment_method"
                                                id="payment-transfer" value="Transfer"
                                                {{ old('payment_method', $customer->payment_method) == 'Transfer' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="payment-transfer">Transfer</label>
                                        </div>
                                        @error('payment_method')
                                        <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="payment_term" class="form-label">Jangka Waktu Pembayaran</label>
                                        <input type="text"
                                            class="form-control @error('payment_term') is-invalid @enderror"
                                            id="payment_term" name="payment_term"
                                            value="{{ old('payment_term', $customer->payment_term) }}"
                                            placeholder="Contoh: 30 Hari setelah invoice">
                                        @error('payment_term')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="payment_estimate" class="form-label">Estimasi Kebutuhan</label>
                                        <div class="input-group">
                                            <input type="text"
                                                class="form-control @error('payment_estimate') is-invalid @enderror"
                                                id="payment_estimate" name="payment_estimate"
                                                value="{{ old('payment_estimate', $customer->payment_estimate) }}"
                                                placeholder="Contoh: 2">
                                            <div class="input-group-text">
                                                <div class="form-check form-check-inline mb-0">
                                                    <input class="form-check-input" type="radio" name="estimate_unit"
                                                        id="estimate-week" value="minggu"
                                                        {{ old('estimate_unit', $customer->estimate_unit) == 'minggu' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="estimate-week">/Minggu</label>
                                                </div>
                                                <div class="form-check form-check-inline mb-0">
                                                    <input class="form-check-input" type="radio" name="estimate_unit"
                                                        id="estimate-month" value="bulan"
                                                        {{ old('estimate_unit', $customer->estimate_unit) == 'bulan' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="estimate-month">/Bulan</label>
                                                </div>
                                            </div>
                                        </div>
                                        @error('payment_estimate')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                        @error('estimate_unit')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="minimum_order" class="form-label">Minimal Order</label>
                                        <input type="text"
                                            class="form-control @error('minimum_order') is-invalid @enderror"
                                            id="minimum_order" name="minimum_order"
                                            value="{{ old('minimum_order', $customer->minimum_order) }}"
                                            placeholder="/Hari atau sekali pengiriman">
                                        @error('minimum_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Sistem Tukar Faktur & Rekening Bank Customer --}}
                        <div class="invoice-bank-system mt-5">
                            <h6 class="mb-3 p-2 bg-gradient-dark text-white rounded d-inline-flex align-items-center">
                                <i class="fas fa-file-invoice-dollar me-2"></i>
                                {{ __('Sistem Tukar Faktur & Rekening Bank Customer') }}
                            </h6>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="invoice_day_time" class="form-label">Hari & Jam Tukar Faktur</label>
                                        <input type="text"
                                            class="form-control @error('invoice_day_time') is-invalid @enderror"
                                            id="invoice_day_time" name="invoice_day_time"
                                            value="{{ old('invoice_day_time', $customer->invoice_day_time) }}"
                                            placeholder="Contoh: Senin & Rabu, pukul 13.00 - 16.00">
                                        @error('invoice_day_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="invoice_address" class="form-label">Alamat Tukar Faktur</label>
                                        <input type="text"
                                            class="form-control @error('invoice_address') is-invalid @enderror"
                                            id="invoice_address" name="invoice_address"
                                            value="{{ old('invoice_address', $customer->invoice_address) }}"
                                            placeholder="Masukkan alamat lengkap">
                                        @error('invoice_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="invoice_pic_name" class="form-label">PIC Tukar Faktur (Nama)</label>
                                        <input type="text"
                                            class="form-control @error('invoice_pic_name') is-invalid @enderror"
                                            id="invoice_pic_name" name="invoice_pic_name"
                                            value="{{ old('invoice_pic_name', $customer->invoice_pic_name) }}"
                                            placeholder="Nama PIC">
                                        @error('invoice_pic_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="invoice_pic_phone" class="form-label">PIC Tukar Faktur (Telepon /
                                            WhatsApp)</label>
                                        <input type="number"
                                            class="form-control @error('invoice_pic_phone') is-invalid @enderror"
                                            id="invoice_pic_phone" name="invoice_pic_phone"
                                            value="{{ old('invoice_pic_phone', $customer->invoice_pic_phone) }}"
                                            placeholder="08123456789">
                                        @error('invoice_pic_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="account_name" class="form-label">Nama Rekening Bank</label>
                                        <input type="text"
                                            class="form-control @error('account_name') is-invalid @enderror"
                                            id="account_name" name="account_name"
                                            value="{{ old('account_name', $customer->account_name) }}"
                                            placeholder="Contoh: PT Maju Jaya">
                                        @error('account_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="account_number" class="form-label">Nomor Rekening Bank</label>
                                        <input type="number"
                                            class="form-control @error('account_number') is-invalid @enderror"
                                            id="account_number" name="account_number"
                                            value="{{ old('account_number', $customer->account_number) }}"
                                            placeholder="Contoh: 1234567890">
                                        @error('account_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="bank_name" class="form-label">Nama Bank</label>
                                        <input type="text" class="form-control @error('bank_name') is-invalid @enderror"
                                            id="bank_name" name="bank_name"
                                            value="{{ old('bank_name', $customer->bank_name) }}"
                                            placeholder="Contoh: BCA, Mandiri">
                                        @error('bank_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="bank_branch" class="form-label">Cabang</label>
                                        <input type="text"
                                            class="form-control @error('bank_branch') is-invalid @enderror"
                                            id="bank_branch" name="bank_branch"
                                            value="{{ old('bank_branch', $customer->bank_branch) }}"
                                            placeholder="Contoh: Cabang Sudirman">
                                        @error('bank_branch')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="bank_address" class="form-label">Alamat Bank</label>
                                        <textarea class="form-control @error('bank_address') is-invalid @enderror"
                                            id="bank_address" name="bank_address" rows="3"
                                            placeholder="Alamat lengkap kantor cabang bank">{{ old('bank_address', $customer->bank_address) }}</textarea>
                                        @error('bank_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Alamat Pengiriman --}}
                        <div class="delivery-address-section mt-5">
                            <h6 class="mb-3 p-2 bg-gradient-dark text-white rounded d-inline-flex align-items-center">
                                <i class="fas fa-map-marker-alt me-2"></i> Alamat Pengiriman
                            </h6>
                            <div class="border rounded p-3 mb-4">
                                <h6 class="fw-semibold mb-3">Alamat 1</h6>
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="delivery_address_1" class="form-label">Alamat Lengkap</label>
                                            <textarea
                                                class="form-control @error('delivery_address_1') is-invalid @enderror"
                                                id="delivery_address_1" name="delivery_address_1" rows="3"
                                                placeholder="Alamat lengkap pengiriman 1">{{ old('delivery_address_1', $customer->delivery_address_1 ?? '') }}</textarea>
                                            @error('delivery_address_1')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="delivery_email_1" class="form-label">Email</label>
                                        <input type="email"
                                            class="form-control @error('delivery_email_1') is-invalid @enderror"
                                            id="delivery_email_1" name="delivery_email_1"
                                            value="{{ old('delivery_email_1', $customer->delivery_email_1 ?? '') }}"
                                            placeholder="Email penerima alamat 1">
                                        @error('delivery_email_1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <label for="delivery_postal_1" class="form-label">Kode Pos</label>
                                        <input type="text"
                                            class="form-control @error('delivery_postal_1') is-invalid @enderror"
                                            id="delivery_postal_1" name="delivery_postal_1"
                                            value="{{ old('delivery_postal_1', $customer->delivery_postal_1 ?? '') }}"
                                            placeholder="Kode Pos">
                                        @error('delivery_postal_1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label for="delivery_time_1" class="form-label">Jam Penerimaan</label>
                                        <input type="text"
                                            class="form-control @error('delivery_time_1') is-invalid @enderror"
                                            id="delivery_time_1" name="delivery_time_1"
                                            value="{{ old('delivery_time_1', $customer->delivery_time_1 ?? '') }}"
                                            placeholder="Contoh: 08.00 - 16.00">
                                        @error('delivery_time_1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label for="delivery_pic_1" class="form-label">PIC Penerima</label>
                                        <input type="text"
                                            class="form-control @error('delivery_pic_1') is-invalid @enderror"
                                            id="delivery_pic_1" name="delivery_pic_1"
                                            value="{{ old('delivery_pic_1', $customer->delivery_pic_1 ?? '') }}"
                                            placeholder="Nama PIC penerima">
                                        @error('delivery_pic_1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="delivery_phone_1" class="form-label">No. HP / WhatsApp</label>
                                        <input type="number"
                                            class="form-control @error('delivery_phone_1') is-invalid @enderror"
                                            id="delivery_phone_1" name="delivery_phone_1"
                                            value="{{ old('delivery_phone_1', $customer->delivery_phone_1 ?? '') }}"
                                            placeholder="Nomor HP/WA penerima">
                                        @error('delivery_phone_1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="border rounded p-3">
                                <h6 class="fw-semibold mb-3">Alamat 2</h6>
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="delivery_address_2" class="form-label">Alamat Lengkap</label>
                                            <textarea
                                                class="form-control @error('delivery_address_2') is-invalid @enderror"
                                                id="delivery_address_2" name="delivery_address_2" rows="3"
                                                placeholder="Alamat lengkap pengiriman 2">{{ old('delivery_address_2', $customer->delivery_address_2 ?? '') }}</textarea>
                                            @error('delivery_address_2')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="delivery_email_2" class="form-label">Email</label>
                                        <input type="email"
                                            class="form-control @error('delivery_email_2') is-invalid @enderror"
                                            id="delivery_email_2" name="delivery_email_2"
                                            value="{{ old('delivery_email_2', $customer->delivery_email_2 ?? '') }}"
                                            placeholder="Email penerima alamat 2">
                                        @error('delivery_email_2')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <label for="delivery_postal_2" class="form-label">Kode Pos</label>
                                        <input type="text"
                                            class="form-control @error('delivery_postal_2') is-invalid @enderror"
                                            id="delivery_postal_2" name="delivery_postal_2"
                                            value="{{ old('delivery_postal_2', $customer->delivery_postal_2 ?? '') }}"
                                            placeholder="Kode Pos">
                                        @error('delivery_postal_2')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label for="delivery_time_2" class="form-label">Jam Penerimaan</label>
                                        <input type="text"
                                            class="form-control @error('delivery_time_2') is-invalid @enderror"
                                            id="delivery_time_2" name="delivery_time_2"
                                            value="{{ old('delivery_time_2', $customer->delivery_time_2 ?? '') }}"
                                            placeholder="Contoh: 09.00 - 15.00">
                                        @error('delivery_time_2')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label for="delivery_pic_2" class="form-label">PIC Penerima</label>
                                        <input type="text"
                                            class="form-control @error('delivery_pic_2') is-invalid @enderror"
                                            id="delivery_pic_2" name="delivery_pic_2"
                                            value="{{ old('delivery_pic_2', $customer->delivery_pic_2 ?? '') }}"
                                            placeholder="Nama PIC penerima">
                                        @error('delivery_pic_2')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="delivery_phone_2" class="form-label">No. HP / WhatsApp</label>
                                        <input type="number"
                                            class="form-control @error('delivery_phone_2') is-invalid @enderror"
                                            id="delivery_phone_2" name="delivery_phone_2"
                                            value="{{ old('delivery_phone_2', $customer->delivery_phone_2 ?? '') }}"
                                            placeholder="Nomor HP/WA penerima">
                                        @error('delivery_phone_2')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Kelengkapan Dokumen --}}
                        <div class="document-completeness-section mt-5">
                            <h6 class="mb-3 p-2 bg-gradient-dark text-white rounded d-inline-flex align-items-center">
                                <i class="fas fa-folder-open me-2"></i> Kelengkapan Dokumen
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="copy_ktp" id="copy_ktp"
                                            {{ old('copy_ktp', $customer->copy_ktp) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="copy_ktp">Fotocopy KTP Pemilik</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="copy_npwp" id="copy_npwp"
                                            {{ old('copy_npwp', $customer->copy_npwp) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="copy_npwp">Fotocopy NPWP</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="copy_tdp" id="copy_tdp"
                                            {{ old('copy_tdp', $customer->copy_tdp) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="copy_tdp">Fotocopy TDP</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="copy_akta" id="copy_akta"
                                            {{ old('copy_akta', $customer->copy_akta) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="copy_akta">Fotocopy Akta Pendirian
                                            Perusahaan</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="copy_domisili"
                                            id="copy_domisili"
                                            {{ old('copy_domisili', $customer->copy_domisili) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="copy_domisili">Fotocopy Domisili
                                            Usaha</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="copy_siup" id="copy_siup"
                                            {{ old('copy_siup', $customer->copy_siup) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="copy_siup">Fotocopy SIUP</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="company_profile"
                                            id="company_profile"
                                            {{ old('company_profile', $customer->company_profile) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="company_profile">Company Profile</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="other_docs"
                                            id="other_docs"
                                            {{ old('other_docs', $customer->other_docs) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="other_docs">Lainnya</label>
                                    </div>
                                </div>
                            </div>
                        </div>


                        {{-- Logo Customer --}}
                        <div class="form-group">
                            <label for="photo">{{ 'Logo Customer' }}</label>
                            <div class="@error('photo') border border-danger rounded-3 @enderror">
                                <div class="d-flex align-items-center">
                                    <div id="imagePreview" class="zoomable"
                                        style="border: 2px dashed #DE0A93; border-radius: 8px; width: 43px; height: 43px;
                                                background-color: #ffffff; background-position: center; background-size: cover; background-repeat: no-repeat;
                                                background-image: url('{{ asset($customer->photo ?? 'assets/img/default-profile.jpg') }}');">
                                    </div>

                                    <input type="file" id="imageInput" name="photo" accept="image/*" class="d-none"
                                        onchange="previewSelectedImage(event)">

                                    <div class="flex-grow-1 ms-3 pt-3">
                                        <a href="javascript:;" onclick="document.getElementById('imageInput').click()"
                                            class="btn bg-gradient-primary w-100">
                                            <i class="fa fa-images pe-2" data-bs-toggle="tooltip"
                                                title="Upload Image"></i>Unggah Logo
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit"
                                class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ 'Update Data' }}&nbsp;&nbsp;<i
                                    class="fa fa-save"></i></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="tab-pane" id="price" role="tabpanel" aria-labelledby="price-tab">
        <div class="container-fluid py-4">
            <form action="{{ route('customer.update.price', $customer->id) }}" method="POST" role="form text-left"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header pb-0 p-4 d-flex justify-content-between">
                        <h6 class="mb-0">{{ __('Update Harga Customer') }}</h6>

                        <a href="{{ route('customer.index') }}"
                            class="btn bg-gradient-danger btn-md mb-0">{{ 'Kembali' }}&nbsp;&nbsp;<i
                                class="fa fa-arrow-right"></i></a>
                    </div>
                    <div class="card-body pt-4 p-4">
                        @if($errors->any())
                        <div class="alert alert-primary alert-dismissible fade show" role="alert">
                            <span class="alert-text text-white">
                                {{$errors->first()}}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                <i class="fa fa-close" aria-hidden="true"></i>
                            </button>
                        </div>
                        @endif
                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" id="alert-success" role="alert">
                            <span class="alert-text text-white">
                                {{ session('success') }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                <i class="fa fa-close" aria-hidden="true"></i>
                            </button>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="periode-name"
                                        class="form-control-label">{{ __('Nama Periode') }}</label>
                                    <div class="@error('periode_name')border border-danger rounded-3 @enderror">
                                        <input class="form-control" type="text" id="periode-name" name="periode_name"
                                            placeholder="Nama Periode"
                                            value="{{ old('periode_name', optional($periode)->periode_name) }}">
                                        @error('periode_name')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="start-date" class="form-control-label">{{ __('Tanggal Awal') }}</label>
                                    <div class="@error('start_date') border border-danger rounded-3 @enderror">
                                        <input class="form-control datepicker" type="text" id="start-date"
                                            name="start_date"
                                            value="{{ old('start_date', optional($periode)->start_date) }}"
                                            placeholder="Tanggal Awal" autocomplete="off">
                                        @error('start_date')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="end-date" class="form-control-label">{{ __('Tanggal Akhir') }}</label>
                                    <div class="@error('start_date') border border-danger rounded-3 @enderror">
                                        <input class="form-control datepicker" type="text" id="end-date" name="end_date"
                                            placeholder="Tanggal Akhir"
                                            value="{{ old('end_date', optional($periode)->end_date) }}"
                                            autocomplete="off">
                                        @error('end_date')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            @foreach($goods as $index => $item)
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="@error('goods_id') border border-danger rounded-3 @enderror">
                                        <label for="goods-name"
                                            class="form-control-label">{{ __('Barang Komoditi') }}</label>

                                        {{-- Hidden input to send goods_id --}}
                                        <input type="hidden" name="goods_id[]" value="{{ $item->id }}">

                                        {{-- Visible but disabled input for display only --}}
                                        <input class="form-control" type="text" id="goods-name"
                                            value="{{ $item->name }}" disabled>

                                        @error('goods_id')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="goods_unit" class="form-control-label">{{ __('Satuan') }}</label>
                                        <div class="@error('goods_unit') border border-danger rounded-3 @enderror">
                                            <input class="form-control" type="text" id="goods_unit" name="goods_unit"
                                                value="{{ $item->unit }}" disabled>
                                            @error('goods_unit')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="goods-price" class="form-control-label">{{ __('Harga') }}</label>
                                        <div class="@error('price') border border-danger rounded-3 @enderror">
                                            <input class="form-control" type="number" step="1000" placeholder="0"
                                                id="goods-price" name="price[]"
                                                value="{{ old('price.' . $index, $item->price) }}">
                                            @error('price')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                            <div class="d-flex justify-content-end">
                                <button type="submit"
                                    class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ 'Update Harga' }}&nbsp;&nbsp;<i
                                        class="fa fa-save"></i></button>
                            </div>
                        </div>
                    </div>
            </form>
        </div>
    </div>
</div>

@include('components.modal.crop-image-modal')

<script>
    let cropper;
    const input = document.getElementById('imageInput');
    const preview = document.getElementById('imagePreview');
    const cropImage = document.getElementById('cropperImage');
    const cropModal = new bootstrap.Modal(document.getElementById('cropModal'));

    function previewSelectedImage(event) {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            cropImage.src = e.target.result;
            cropModal.show();
        };
        reader.readAsDataURL(file);
    }

    document.getElementById('cropModal').addEventListener('shown.bs.modal', function () {
        cropper = new Cropper(cropImage, {
            aspectRatio: 1,
            viewMode: 1,
            responsive: true,
        });
    });

    document.getElementById('cropModal').addEventListener('hidden.bs.modal', function () {
        cropper.destroy();
        cropper = null;
    });

    document.getElementById('cropButton').addEventListener('click', function () {
        const canvas = cropper.getCroppedCanvas({
            width: 200,
            height: 200,
        });

        canvas.toBlob(function (blob) {
            const url = URL.createObjectURL(blob);
            preview.style.backgroundImage = `url('${url}')`;

            // Jika ingin submit ke server, simpan blob di FormData
            const file = new File([blob], "cropped.jpg", { type: "image/jpeg" });

            // Gantikan file asli agar bisa dikirim lewat form biasa
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            input.files = dataTransfer.files;

            cropModal.hide();
        }, 'image/jpeg');
    });

    flatpickr(".datepicker", {
        locale: "id",
        altInput: true,
        altFormat: "l, d F Y",
        dateFormat: "Y-m-d",
        onReady: function (selectedDates, dateStr, instance) {
            if (instance.altInput) {
                instance.altInput.setAttribute("style", "background-color: white; cursor: text;");
            }
        }
    });
</script>

@endsection