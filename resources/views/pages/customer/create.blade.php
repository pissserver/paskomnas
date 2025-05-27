@extends('layouts.user_type.auth')

@section('title', 'Tambah Customer Baru')

@section('content')

<div>
    <form action="{{ route('customer.store') }}" method="POST" role="form text-left" enctype="multipart/form-data">
        <div class="container-fluid">
            @csrf
            @method('PUT')
            <div class="container-fluid py-4">
                <div class="card">
                    <div class="card-header p-4 d-flex justify-content-between">
                        <h6 class="mb-0">{{ __('Form Customer Baru') }}</h6>
                        <a href="{{ route('customer.index') }}"
                            class="btn bg-gradient-danger btn-md mb-0">{{ 'Kembali' }}&nbsp;&nbsp;<i
                                class="fa fa-arrow-right"></i></a>
                    </div>
                    <div class="card-body pt-2 p-4">
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
                                        <label for="customer-name">Nama Perusahaan</label>
                                        <div class="@error('customer.name')border border-danger rounded-3 @enderror">
                                            <input class="form-control" type="text" placeholder="Nama Perusahaan"
                                                id="customer-name" name="name" value="{{ old('name') }}">
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
                                                <input class="form-check-input" type="radio" name="status" value="pusat"
                                                    id="status-pusat" {{ old('status') == 'pusat' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="status-pusat">Pusat</label>
                                            </div>
                                            <div class="form-check me-4">
                                                <input class="form-check-input" type="radio" name="status"
                                                    value="cabang" id="status-cabang"
                                                    {{ old('status') == 'cabang' ? 'checked' : '' }}>
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
                                        <label for="alamat-kantor">Alamat Kantor</label>
                                        <div class="@error('alamat_kantor') border border-danger rounded-3 @enderror">
                                            <textarea class="form-control" placeholder="Alamat Kantor"
                                                id="alamat-kantor" name="alamat_kantor"
                                                rows="3">{{ old('alamat_kantor') }}</textarea>
                                            @error('alamat_kantor')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="alamat-surat">Alamat Surat Menyurat</label>
                                        <div class="@error('alamat_surat') border border-danger rounded-3 @enderror">
                                            <textarea class="form-control" placeholder="Alamat Surat Menyurat"
                                                id="alamat-surat" name="alamat_surat"
                                                rows="3">{{ old('alamat_surat') }}</textarea>
                                            @error('alamat_surat')
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
                                            <input class="form-control" type="text" placeholder="No. Telepon Kantor"
                                                id="no-telp" name="no_telp" value="{{ old('no_telp') }}">
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
                                                id="email" name="email" value="{{ old('email') }}">
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
                                            class="@error('kategori') border border-danger rounded-3 p-2 @enderror d-flex">
                                            <div class="form-check me-4">
                                                <input class="form-check-input" type="radio" name="kategori"
                                                    value="perseorangan" id="kategori-perseorangan"
                                                    {{ old('kategori') == 'perseorangan' ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="kategori-perseorangan">Perseorangan</label>
                                            </div>
                                            <div class="form-check me-4">
                                                <input class="form-check-input" type="radio" name="kategori"
                                                    value="badan_usaha" id="kategori-badan-usaha"
                                                    {{ old('kategori') == 'badan_usaha' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="kategori-badan-usaha">Badan
                                                    Usaha</label>
                                            </div>
                                        </div>
                                        @error('kategori')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Jenis Usaha</label>
                                        <div
                                            class="@error('jenis_usaha') border border-danger rounded-3 p-2 @enderror d-flex flex-wrap">
                                            <div class="form-check me-4">
                                                <input class="form-check-input" type="radio" name="jenis_usaha"
                                                    value="restoran" id="usaha-restoran"
                                                    {{ old('jenis_usaha') == 'restoran' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="usaha-restoran">Restoran / Rumah
                                                    Makan</label>
                                            </div>
                                            <div class="form-check me-4">
                                                <input class="form-check-input" type="radio" name="jenis_usaha"
                                                    value="katering" id="usaha-katering"
                                                    {{ old('jenis_usaha') == 'katering' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="usaha-katering">Katering</label>
                                            </div>
                                            <div class="form-check me-4">
                                                <input class="form-check-input" type="radio" name="jenis_usaha"
                                                    value="hotel" id="usaha-hotel"
                                                    {{ old('jenis_usaha') == 'hotel' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="usaha-hotel">Hotel</label>
                                            </div>
                                            <div class="form-check me-4">
                                                <input class="form-check-input" type="radio" name="jenis_usaha"
                                                    value="lainnya" id="usaha-lainnya"
                                                    {{ old('jenis_usaha') == 'lainnya' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="usaha-lainnya">Lainnya</label>
                                            </div>
                                        </div>
                                        @error('jenis_usaha')
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
                                                name="npwp" value="{{ old('npqp') }}">
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
                                        <label for="alasan">Alasan Menggunakan</label>
                                        <div class="@error('alasan') border border-danger rounded-3 @enderror">
                                            <textarea class="form-control" placeholder="Alasan menggunakan layanan ini"
                                                id="alasan" name="alasan" rows="4">{{ old('alasan') }}</textarea>
                                            @error('alasan')
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
                                            id="owner-name" name="owner_name" value="{{ old('owner_name') }}"
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
                                            id="owner-email" name="owner_email" value="{{ old('owner_email') }}"
                                            placeholder="Email Pemilik">
                                        @error('owner_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="owner-phone" class="form-label">Telepon</label>
                                        <input type="text"
                                            class="form-control @error('owner_phone') is-invalid @enderror"
                                            id="owner-phone" name="owner_phone" value="{{ old('owner_phone') }}"
                                            placeholder="Telepon Pemilik">
                                        @error('owner_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="owner-whatsapp" class="form-label">WhatsApp</label>
                                        <input type="text"
                                            class="form-control @error('owner_whatsapp') is-invalid @enderror"
                                            id="owner-whatsapp" name="owner_whatsapp"
                                            value="{{ old('owner_whatsapp') }}" placeholder="WhatsApp Pemilik">
                                        @error('owner_whatsapp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="purchasing-name" class="form-label">Nama</label>
                                        <input type="text"
                                            class="form-control @error('purchasing_name') is-invalid @enderror"
                                            id="purchasing-name" name="purchasing_name"
                                            value="{{ old('purchasing_name') }}"
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
                                            value="{{ old('purchasing_email') }}"
                                            placeholder="Email Penanggung Jawab Purchasing">
                                        @error('purchasing_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="purchasing-phone" class="form-label">Telepon</label>
                                        <input type="text"
                                            class="form-control @error('purchasing_phone') is-invalid @enderror"
                                            id="purchasing-phone" name="purchasing_phone"
                                            value="{{ old('purchasing_phone') }}"
                                            placeholder="Telepon Penanggung Jawab">
                                        @error('purchasing_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="purchasing-whatsapp" class="form-label">WhatsApp</label>
                                        <input type="text"
                                            class="form-control @error('purchasing_whatsapp') is-invalid @enderror"
                                            id="purchasing-whatsapp" name="purchasing_whatsapp"
                                            value="{{ old('purchasing_whatsapp') }}"
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
                                        <label for="finance-name" class="form-label">Nama</label>
                                        <input type="text"
                                            class="form-control @error('finance_name') is-invalid @enderror"
                                            id="finance-name" name="finance_name" value="{{ old('finance_name') }}"
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
                                            id="finance-email" name="finance_email" value="{{ old('finance_email') }}"
                                            placeholder="Email Penanggung Jawab Keuangan">
                                        @error('finance_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="finance-phone" class="form-label">Telepon</label>
                                        <input type="text"
                                            class="form-control @error('finance_phone') is-invalid @enderror"
                                            id="finance-phone" name="finance_phone" value="{{ old('finance_phone') }}"
                                            placeholder="Telepon Penanggung Jawab Keuangan">
                                        @error('finance_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="finance-whatsapp" class="form-label">WhatsApp</label>
                                        <input type="text"
                                            class="form-control @error('finance_whatsapp') is-invalid @enderror"
                                            id="finance-whatsapp" name="finance_whatsapp"
                                            value="{{ old('finance_whatsapp') }}"
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
                                                {{ old('payment_method') == 'COD' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="payment-cod">COD / Cash</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="payment_method"
                                                id="payment-transfer" value="Transfer"
                                                {{ old('payment_method') == 'Transfer' ? 'checked' : '' }}>
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
                                            id="payment_term" name="payment_term" value="{{ old('payment_term') }}"
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
                                                value="{{ old('payment_estimate') }}" placeholder="Contoh: 2">
                                            <div class="input-group-text">
                                                <div class="form-check form-check-inline mb-0">
                                                    <input class="form-check-input" type="radio" name="estimate_unit"
                                                        id="estimate-week" value="minggu"
                                                        {{ old('estimate_unit') == 'minggu' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="estimate-week">/Minggu</label>
                                                </div>
                                                <div class="form-check form-check-inline mb-0">
                                                    <input class="form-check-input" type="radio" name="estimate_unit"
                                                        id="estimate-month" value="bulan"
                                                        {{ old('estimate_unit') == 'bulan' ? 'checked' : '' }}>
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
                                            id="minimum_order" name="minimum_order" value="{{ old('minimum_order') }}"
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
                                            value="{{ old('invoice_day_time') }}"
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
                                            value="{{ old('invoice_address') }}" placeholder="Masukkan alamat lengkap">
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
                                            value="{{ old('invoice_pic_name') }}" placeholder="Nama PIC">
                                        @error('invoice_pic_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="invoice_pic_phone" class="form-label">PIC Tukar Faktur (Telepon /
                                            WhatsApp)</label>
                                        <input type="text"
                                            class="form-control @error('invoice_pic_phone') is-invalid @enderror"
                                            id="invoice_pic_phone" name="invoice_pic_phone"
                                            value="{{ old('invoice_pic_phone') }}" placeholder="08123456789">
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
                                            id="account_name" name="account_name" value="{{ old('account_name') }}"
                                            placeholder="Contoh: PT Maju Jaya">
                                        @error('account_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="account_number" class="form-label">Nomor Rekening Bank</label>
                                        <input type="text"
                                            class="form-control @error('account_number') is-invalid @enderror"
                                            id="account_number" name="account_number"
                                            value="{{ old('account_number') }}" placeholder="Contoh: 1234567890">
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
                                            id="bank_name" name="bank_name" value="{{ old('bank_name') }}"
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
                                            id="bank_branch" name="bank_branch" value="{{ old('bank_branch') }}"
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
                                            placeholder="Alamat lengkap kantor cabang bank">{{ old('bank_address') }}</textarea>
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
                                                placeholder="Alamat lengkap pengiriman 1">{{ old('delivery_address_1') }}</textarea>
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
                                            value="{{ old('delivery_email_1') }}" placeholder="Email penerima alamat 1">
                                        @error('delivery_email_1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <label for="delivery_postal_1" class="form-label">Kode Pos</label>
                                        <input type="text"
                                            class="form-control @error('delivery_postal_1') is-invalid @enderror"
                                            id="delivery_postal_1" name="delivery_postal_1"
                                            value="{{ old('delivery_postal_1') }}" placeholder="Kode Pos">
                                        @error('delivery_postal_1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label for="delivery_time_1" class="form-label">Jam Penerimaan</label>
                                        <input type="text"
                                            class="form-control @error('delivery_time_1') is-invalid @enderror"
                                            id="delivery_time_1" name="delivery_time_1"
                                            value="{{ old('delivery_time_1') }}" placeholder="Contoh: 08.00 - 16.00">
                                        @error('delivery_time_1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label for="delivery_pic_1" class="form-label">PIC Penerima</label>
                                        <input type="text"
                                            class="form-control @error('delivery_pic_1') is-invalid @enderror"
                                            id="delivery_pic_1" name="delivery_pic_1"
                                            value="{{ old('delivery_pic_1') }}" placeholder="Nama PIC penerima">
                                        @error('delivery_pic_1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="delivery_phone_1" class="form-label">No. HP / WhatsApp</label>
                                        <input type="text"
                                            class="form-control @error('delivery_phone_1') is-invalid @enderror"
                                            id="delivery_phone_1" name="delivery_phone_1"
                                            value="{{ old('delivery_phone_1') }}" placeholder="Nomor HP/WA penerima">
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
                                                placeholder="Alamat lengkap pengiriman 2">{{ old('delivery_address_2') }}</textarea>
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
                                            value="{{ old('delivery_email_2') }}" placeholder="Email penerima alamat 2">
                                        @error('delivery_email_2')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <label for="delivery_postal_2" class="form-label">Kode Pos</label>
                                        <input type="text"
                                            class="form-control @error('delivery_postal_2') is-invalid @enderror"
                                            id="delivery_postal_2" name="delivery_postal_2"
                                            value="{{ old('delivery_postal_2') }}" placeholder="Kode Pos">
                                        @error('delivery_postal_2')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label for="delivery_time_2" class="form-label">Jam Penerimaan</label>
                                        <input type="text"
                                            class="form-control @error('delivery_time_2') is-invalid @enderror"
                                            id="delivery_time_2" name="delivery_time_2"
                                            value="{{ old('delivery_time_2') }}" placeholder="Contoh: 09.00 - 15.00">
                                        @error('delivery_time_2')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label for="delivery_pic_2" class="form-label">PIC Penerima</label>
                                        <input type="text"
                                            class="form-control @error('delivery_pic_2') is-invalid @enderror"
                                            id="delivery_pic_2" name="delivery_pic_2"
                                            value="{{ old('delivery_pic_2') }}" placeholder="Nama PIC penerima">
                                        @error('delivery_pic_2')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="delivery_phone_2" class="form-label">No. HP / WhatsApp</label>
                                        <input type="text"
                                            class="form-control @error('delivery_phone_2') is-invalid @enderror"
                                            id="delivery_phone_2" name="delivery_phone_2"
                                            value="{{ old('delivery_phone_2') }}" placeholder="Nomor HP/WA penerima">
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
                                        <input class="form-check-input" type="checkbox" name="copy_ktp" id="copy_ktp">
                                        <label class="form-check-label" for="copy_ktp">Fotocopy KTP Pemilik</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="copy_npwp" id="copy_npwp">
                                        <label class="form-check-label" for="copy_npwp">Fotocopy NPWP</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="copy_tdp" id="copy_tdp">
                                        <label class="form-check-label" for="copy_tdp">Fotocopy TDP</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="copy_akta" id="copy_akta">
                                        <label class="form-check-label" for="copy_akta">Fotocopy Akta Pendirian
                                            Perusahaan</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="copy_domisili"
                                            id="copy_domisili">
                                        <label class="form-check-label" for="copy_domisili">Fotocopy Domisili
                                            Usaha</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="copy_siup" id="copy_siup">
                                        <label class="form-check-label" for="copy_siup">Fotocopy SIUP</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="company_profile"
                                            id="company_profile">
                                        <label class="form-check-label" for="company_profile">Company Profile</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="other_docs"
                                            id="other_docs">
                                        <label class="form-check-label" for="other_docs">Lainnya</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Logo Customer --}}
                        <div class="row mt-5">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <h6
                                        class="mb-3 p-2 bg-gradient-dark text-white rounded d-inline-flex align-items-center">
                                        <i class="fas fa-image me-2"></i> Logo Customer
                                    </h6>
                                    <div class="@error('photo') border border-danger rounded-3 @enderror">
                                        <div class="d-flex align-items-center">
                                            <div id="imagePreview" class="zoomable"
                                                style="border: 2px dashed #DE0A93; border-radius: 8px; width: 43px; height: 43px; background-color: #ffffff; background-position: center; background-size: cover; background-repeat: no-repeat;">
                                            </div>

                                            <input type="file" id="imageInput" name="photo" accept="image/*"
                                                class="d-none" onchange="previewSelectedImage(event)">

                                            <div class="flex-grow-1 ms-3 pt-3">
                                                <a href="javascript:;"
                                                    onclick="document.getElementById('imageInput').click()"
                                                    class="btn bg-gradient-primary w-100">
                                                    <i class="fa fa-image pe-2" data-bs-toggle="tooltip"
                                                        title="Upload Image"></i>Unggah
                                                    Logo
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit"
                                class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ 'Simpan' }}&nbsp;&nbsp;<i
                                    class="fa fa-save"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@include('components.modal.crop-image-modal')

<script>
    let cropper;
    const cropperImage = document.getElementById('cropperImage');
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');
    const cropModal = new bootstrap.Modal(document.getElementById('cropModal'));

    function previewSelectedImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                cropperImage.src = e.target.result;
                cropModal.show();

                // Inisialisasi cropper setelah modal muncul
                setTimeout(() => {
                    if (cropper) cropper.destroy();
                    cropper = new Cropper(cropperImage, {
                        aspectRatio: 1,
                        viewMode: 1,
                        autoCropArea: 1,
                        background: false,
                        responsive: true,
                    });
                }, 300);
            };
            reader.readAsDataURL(file);
        }
    }

    document.getElementById('cropButton').addEventListener('click', () => {
        const canvas = cropper.getCroppedCanvas({
            width: 300,
            height: 300,
        });

        canvas.toBlob(blob => {
            const url = URL.createObjectURL(blob);
            imagePreview.style.backgroundImage = `url('${url}')`;

            // Replace input file dengan blob hasil crop
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(new File([blob], 'cropped.jpg', { type: 'image/jpeg' }));
            imageInput.files = dataTransfer.files;

            cropModal.hide();
        }, 'image/jpeg');
    });
</script>
@endsection