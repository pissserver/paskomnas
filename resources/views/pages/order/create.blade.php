@extends('layouts.user_type.auth')

@section('title', 'Buat Orderan')

@section('content')

<div class="container-fluid py-4">
    <div class="card card-body blur shadow-blur">
        <div class="row gx-4">
            <div class="col-auto">
                <div class="d-flex flex-column align-items-center">
                    <div class="avatar avatar-xl position-relative">
                        <img src="{{ asset($customer->photo) ?? asset('assets/img/default-profile.jpg') }}"
                            alt="{{ $customer->name }}" class="avatar shadow-lg img-fluid"
                            style="width: 80px; height: 80px; object-fit: cover;">
                    </div>
                    <a href="{{ route('customer.edit', $customer->id) }}" class="btn btn-info mt-4 px-2"
                        style="font-size: 0.75rem;">
                        <i class="fas fa-info-circle me-1"></i> Detail
                    </a>
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h6 class="btn bg-gradient-primary"><i class="fa fa-briefcase me-2"></i>{{ $customer->name }}
                    </h6>
                    <p class="text-sm d-flex align-items-center mb-1">
                        <i class="fa fa-map-marker-alt me-2" style="width: 20px; text-align: center;"></i>
                        {{ $customer->address }}
                    </p>
                    <p class="text-sm d-flex align-items-center mb-1">
                        <i class="fa fa-id-card me-2" style="width: 20px; text-align: center;"></i>
                        {{ $customer->npwp }}
                    </p>
                    <p class="text-sm d-flex align-items-center mb-1">
                        <i class="fa fa-phone me-2" style="width: 20px; text-align: center;"></i>
                        {{ $customer->pic_receiver }}
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                <div class="nav-wrapper position-relative">
                    <ul class="nav nav-pills flex-column bg-transparent" role="tablist">
                        <!-- New Order -->
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active mb-2" id="new-order-tab" data-bs-toggle="tab" href="#new-order"
                                role="tab" aria-controls="new-order" aria-selected="true">
                                <i class="fas fa-plus-circle me-1"></i> Order Baru
                            </a>
                        </li>

                        <!-- Template Order -->
                        <li class="nav-item" role="presentation">
                            <a class="nav-link mb-2" id="template-order-tab" data-bs-toggle="tab" href="#template-order"
                                role="tab" aria-controls="template-order" aria-selected="false">
                                <i class="fas fa-copy me-1"></i> Template Order
                            </a>
                        </li>

                        <!-- Data Order -->
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="data-order-tab" data-bs-toggle="tab" href="#data-order" role="tab"
                                aria-controls="data-order" aria-selected="false">
                                <i class="fas fa-shopping-cart me-1"></i> Data Order
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid px-4">
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
</div>

<div class="tab-content">
    <div class="tab-pane fade show active" id="new-order" role="tabpanel" aria-labelledby="new-order-tab">
        <form action="{{ route('order.store', $customer->id) }}" method="POST" role="form text-left"
            enctype="multipart/form-data">
            <div class="container-fluid">
                @csrf
                <div class="container-fluid py-4">
                    <div class="card">
                        <div class="card-body pt-2 p-4">
                            <div class="row pt-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="order-code">Kode Order</label>
                                        @php
                                        $latestOrderCode = optional($orders)->order_code;
                                        $year = now()->format('Y');

                                        // default kalau tidak ada order sebelumnya
                                        $newOrderCode = 'PO'.$customer->id.'-'.$year.'00001';

                                        if ($latestOrderCode && preg_match('/^(.*?)(\d+)$/', $latestOrderCode, $match))
                                        {
                                        $prefix = $match[1];
                                        $number = $match[2];
                                        $newOrderCode = $prefix . str_pad((int)$number + 1, strlen($number), '0',
                                        STR_PAD_LEFT);
                                        }
                                        @endphp

                                        <input type="text" class="form-control" id="order-code"
                                            value="{{ $newOrderCode }}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="order-date">Tanggal Order</label>
                                        <input type="date" class="form-control" id="order-date" name="order_date"
                                            required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="goods-name" class="form-control-label">Nama Barang
                                            Komoditi</label>
                                        <div class="@error('goods_id') border border-danger rounded-3 @enderror">
                                            <select class="form-control select2" id="goods-select" name="goods_id[]">
                                                <option value="">-- Pilih Barang Komoditi --</option>
                                                @foreach ($goods as $item)
                                                <option value="{{ $item->id }}" data-unit="{{ $item->unit }}">
                                                    {{ $item->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('goods_id')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="specification" class="form-control-label">Spesifikasi</label>
                                        <div class="@error('specification') border border-danger rounded-3 @enderror">
                                            <select class="form-control select2-specification" id="specification-select"
                                                name="specification_id[]">
                                                <option value="">-- Pilih Spesifikasi --</option>
                                                @foreach ($specifications as $specification)
                                                <option value="{{ $specification->id }}">
                                                    {{ $specification->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('specification')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="unit" class="form-control-label">{{ __('Satuan') }}</label>
                                        <div class="@error('unit') border border-danger rounded-3 @enderror">
                                            <input class="form-control" type="text" placeholder="Satuan" id="unit-0"
                                                name="unit[]" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="price" class="form-control-label">{{ __('Harga Satuan') }}</label>
                                        <div class="@error('price') border border-danger rounded-3 @enderror">
                                            <input class="form-control" type="number" step="1000"
                                                placeholder="Harga Satuan" name="price[]">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="unit" class="form-control-label">{{ __('Jumlah') }}</label>
                                        <div class="@error('quantity') border border-danger rounded-3 @enderror">
                                            <input class="form-control" type="number" placeholder="0" id="unit"
                                                name="quantity[]">
                                        </div>
                                    </div>
                                </div>

                                <div id="order-items-container"></div>

                                <div class="d-flex justify-content-end mb-4">
                                    <button type="button" class="btn btn-outline-primary btn-sm me-2"
                                        onclick="addOrderItem()">
                                        <i class="fa fa-plus me-2"></i> Tambah Barang
                                    </button>
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <button type="submit" class="btn bg-gradient-dark">
                                        Simpan<i class="fa fa-save ms-2"></i>
                                    </button>
                                    <a href="{{ route('order.index') }}" class="btn bg-gradient-danger">
                                        Kembali<i class="fa fa-arrow-right ms-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="tab-pane fade" id="template-order" role="tabpanel" aria-labelledby="template-order-tab">
        <form action="{{ route('order.store', $customer->id) }}" method="POST" role="form text-left"
            enctype="multipart/form-data">
            <div class="container-fluid">
                @csrf
                <div class="container-fluid py-4">
                    <div class="card">
                        <div class="card-body pt-2 p-4">
                            @if (!is_null($orders))
                            <div class="row pt-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="order-code">Kode Order</label>
                                        @php
                                        $latestOrderCode = optional($orders)->order_code;
                                        $year = now()->format('Y');

                                        // default kalau tidak ada order sebelumnya
                                        $newOrderCode = 'PO'.$customer->id.'-'.$year.'00001';

                                        if ($latestOrderCode && preg_match('/^(.*?)(\d+)$/', $latestOrderCode, $match))
                                        {
                                        $prefix = $match[1];
                                        $number = $match[2];
                                        $newOrderCode = $prefix . str_pad((int)$number + 1, strlen($number), '0',
                                        STR_PAD_LEFT);
                                        }
                                        @endphp

                                        <input type="text" class="form-control" id="order-code"
                                            value="{{ $newOrderCode }}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="order-date">Tanggal Order</label>
                                        <input type="date" class="form-control" id="order-date" name="order_date"
                                            required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div id="order-items-wrapper">
                                    @foreach($orders->orderItems as $index => $orderItem)
                                    <div class="order-item row mb-2" id="order-item-{{ $index }}">
                                        <input type="hidden" name="order_item_id[]" value="{{ $orderItem->id }}">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Nama Barang Komoditi</label>
                                                <select class="form-control select2"
                                                    id="template-goods-select-{{ $index }}" name="goods_id[]"
                                                    onchange="updateTemplateUnitAndPrice({{ $index }})">
                                                    <option value="">-- Pilih Barang Komoditi --</option>
                                                    @foreach ($goods as $item)
                                                    <option value="{{ $item->id }}" data-unit="{{ $item->unit }}"
                                                        {{ $orderItem->goods_id == $item->id ? 'selected' : '' }}>
                                                        {{ $item->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="specification"
                                                    class="form-control-label">Spesifikasi</label>
                                                <div
                                                    class="@error('specification') border border-danger rounded-3 @enderror">
                                                    <select class="form-control select2-specification"
                                                        id="template-specification-select-{{ $index }}"
                                                        name="specification_id[]">
                                                        <option value="">-- Pilih Spesifikasi --</option>
                                                        @foreach ($specifications as $specification)
                                                        <option value="{{ $specification->id }}"
                                                            {{ $orderItem->specification_id == $specification->id ? 'selected' : '' }}>
                                                            {{ $specification->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    @error('specification')
                                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <label>Satuan</label>
                                            <input type="text" class="form-control" id="template-unit-{{ $index }}"
                                                value="{{ $orderItem->goods->unit }}" disabled>
                                        </div>

                                        <div class="col-md-2">
                                            <label>Harga Satuan</label>
                                            <input type="number" step="1000" class="form-control" name="price[]"
                                                value="{{ $orderItem->price }}">
                                        </div>

                                        <div class="col-md-1">
                                            <label>Jumlah</label>
                                            <input type="number" class="form-control" name="quantity[]"
                                                value="{{ $orderItem->quantity }}">
                                        </div>

                                        <div class="col-md-1" style="padding-top: 32px;">
                                            <button type="button" class="btn btn-danger"
                                                onclick="removeOrderItem({{ $index }})">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                {{-- Container item baru --}}
                                <div id="template-order-items-container"></div>

                                <div class="d-flex justify-content-end mb-4">
                                    <button type="button" class="btn btn-outline-primary me-2"
                                        onclick="addTemplateOrderItem()">
                                        <i class="fa fa-plus"></i> Tambah Barang
                                    </button>
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <button type="submit" class="btn bg-gradient-dark">
                                        Simpan<i class="fa fa-save ms-2"></i>
                                    </button>
                                    <a href="{{ route('order.index') }}" class="btn bg-gradient-danger">
                                        Kembali<i class="fa fa-arrow-right ms-2"></i>
                                    </a>
                                </div>
                            </div>
                            @else
                            <div class="empty-data">
                                <div class="text-center">
                                    <img src="{{ asset('assets/img/empty-data.png') }}" alt="No Data" class="img-fluid"
                                        width="450">
                                    <h4 class="text-secondary">Belum ada template order</h4>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="tab-pane fade" id="data-order" role="tabpanel" aria-labelledby="data-order-tab">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4 mx-4">
                        <div class="card-header pb-0">
                            <div class="d-flex flex-row justify-content-between">
                                <div
                                    class="d-flex align-items-center bg-gradient-secondary ps-3 pe-3 rounded-2 text-white">
                                    <i class="fa fa-shopping-cart me-2"></i>
                                    <h6 class="mb-0 text-white">Data Order</h6>
                                </div>
                                <div class="d-flex align-items-center">
                                    <input type="text" class="form-control form-control-sm me-1" id="searchOrder"
                                        placeholder="Cari order..." name="search" style="max-width: 200px;">
                                    <span class="input-group-text"
                                        onclick="togglePassword('password_confirmation', this)">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-4 pb-2">
                            @if($allOrders->isEmpty())
                            <div class="empty-data">
                                <div class="text-center">
                                    <img src="{{ asset('assets/img/empty-data.png') }}" alt="No Data" class="img-fluid"
                                        width="450">
                                    <h4 class="text-secondary">Belum ada data order</h4>
                                </div>
                            </div>
                            @else
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0 table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                No
                                            </th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Kode Order
                                            </th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Tanggal Order
                                            </th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Status
                                            </th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Aksi
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="orderTableBody">

                                    </tbody>
                                </table>
                            </div>
                            @endif
                        </div>

                        <div class="d-flex justify-content-center mt-3 mb-2" id="pagination"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let orderItemCount = 0;
    let orderTemplateItemCount = {{ $orders ? ($orders->orderItems->keys()->last() ?? -1) : -1 }};

    // Inisialisasi Select2 saat dokumen siap
    $(document).ready(function () {
        $('.select2').select2({
            theme: 'bootstrap-5',
            placeholder: "-- Pilih Barang Komoditi --",
            allowClear: true,
            width: '100%'
        });

        $('.select2-specification').select2({
            theme: 'bootstrap-5',
            placeholder: "-- Pilih Spesifikasi --",
            allowClear: true,
            width: '100%'
        });

        // Untuk item pertama (id = 0)
        $('#goods-select').on('change', function () {
            const selected = $(this).find(':selected');
            const unit = selected.data('unit') || '';
            $('#unit-0').val(unit);
        });

        $('#template-goods-select').on('change', function () {
            const selected = $(this).find(':selected');
            const unit = selected.data('unit') || '';
            $('#template-unit-0').val(unit);
        });
    });

    // Fungsi inisialisasi Select2 untuk elemen baru
    function initSelect2(selector) {
        $(selector).select2({
            theme: 'bootstrap-5',
            placeholder: "-- Pilih Barang Komoditi --",
            allowClear: true,
            width: '100%'
        });
    }
    function initSelect2Specification(selector) {
        $(selector).select2({
            theme: 'bootstrap-5',
            placeholder: "-- Pilih Spesifikasi --",
            allowClear: true,
            width: '100%'
        });
    }

    function initTemplateSelect2(selector) {
        $(selector).select2({
            theme: 'bootstrap-5',
            placeholder: "-- Pilih Barang Komoditi --",
            allowClear: true,
            width: '100%'
        });
    }

    function initTemplateSelect2Specification(selector) {
        $(selector).select2({
            theme: 'bootstrap-5',
            placeholder: "-- Pilih Spesifikasi --",
            allowClear: true,
            width: '100%'
        });
    }

    // Fungsi tambah baris orderan baru
    function addOrderItem() {
        orderItemCount++;
        const newItem = `
        <div class="order-item row mb-2" id="order-item-${orderItemCount}">
            <div class="col-md-4">
                <label>Nama Barang Komoditi</label>
                <select class="form-control select2" id="goods-select-${orderItemCount}" name="goods_id[]" onchange="updateUnitAndPrice(${orderItemCount})">
                    <option value="">-- Pilih Barang Komoditi --</option>
                    @foreach ($goods as $item)
                    <option value="{{ $item->id }}" data-unit="{{ $item->unit }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="specification" class="form-control-label">Spesifikasi</label>
                        <div class="@error('specification') border border-danger rounded-3 @enderror">
                            <select class="form-control select2-specification" id="specification-select-${orderItemCount}"
                                name="specification_id[]">
                                <option value="">-- Pilih Spesifikasi --</option>
                                @foreach ($specifications as $specification)
                                <option value="{{ $specification->id }}">
                                    {{ $specification->name }}
                                </option>
                                @endforeach
                            </select>
                        @error('specification')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <label>Satuan</label>
                <input type="text" class="form-control" placeholder="Satuan" id="unit-${orderItemCount}" name="unit[]" disabled>
            </div>
            <div class="col-md-2">
                <label>Harga Satuan</label>
                <input type="number" step="1000" class="form-control" placeholder="Harga Satuan" name="price[]">
            </div>
            <div class="col-md-1">
                <label>Jumlah</label>
                <input type="number" class="form-control" name="quantity[]" placeholder="0">
            </div>
            <div class="col-md-1" style="padding-top: 32px;">
                <button type="button" class="btn btn-danger" onclick="removeOrderItem(${orderItemCount})">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </div>
        `;

        $('#order-items-container').append(newItem);
        initSelect2(`#goods-select-${orderItemCount}`);
        initSelect2Specification(`#specification-select-${orderItemCount}`);
    }

    function addTemplateOrderItem() {
        orderTemplateItemCount++;
        const newItem = `
        <div class="template-order-item row mb-2" id="template-order-item-${orderTemplateItemCount}">
            <div class="col-md-4">
                <label>Nama Barang Komoditi</label>
                <select class="form-control select2" id="template-goods-select-${orderTemplateItemCount}" name="goods_id[]" onchange="updateTemplateUnitAndPrice(${orderTemplateItemCount})">
                    <option value="">-- Pilih Barang Komoditi --</option>
                    @foreach ($goods as $item)
                    <option value="{{ $item->id }}" data-unit="{{ $item->unit }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="specification" class="form-control-label">Spesifikasi</label>
                    <div class="@error('specification') border border-danger rounded-3 @enderror">
                        <select class="form-control select2-specification" id="template-specification-select-${orderTemplateItemCount}"
                            name="specification_id[]">
                            <option value="">-- Pilih Spesifikasi --</option>
                            @foreach ($specifications as $specification)
                            <option value="{{ $specification->id }}">
                                {{ $specification->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('specification')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <label>Satuan</label>
                <input type="text" class="form-control" placeholder="Satuan" id="template-unit-${orderTemplateItemCount}" name="unit[]" disabled>
            </div>
            <div class="col-md-2">
                <label>Harga Satuan</label>
                <input type="number" step="1000" class="form-control" placeholder="Harga Satuan" name="price[]">
            </div>
            <div class="col-md-1">
                <label>Jumlah</label>
                <input type="number" class="form-control" name="quantity[]" placeholder="0">
            </div>
            <div class="col-md-1" style="padding-top: 32px;">
                <button type="button" class="btn btn-danger" onclick="removeTemplateOrderItem(${orderTemplateItemCount})">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </div>
        `;

        $('#template-order-items-container').append(newItem);
        initTemplateSelect2(`#template-goods-select-${orderTemplateItemCount}`);
        initTemplateSelect2Specification(`#template-specification-select-${orderTemplateItemCount}`);
    }

    // Fungsi hapus baris orderan
    function removeOrderItem(id) {
        $(`#order-item-${id}`).remove();
    }

    function removeTemplateOrderItem(id) {
        $(`#template-order-item-${id}`).remove();
    }

    // Fungsi update satuan dan harga berdasarkan barang yang dipilih
    function updateUnitAndPrice(id) {
        const selected = $(`#goods-select-${id}`).find(':selected');
        const unit = selected.data('unit') || '';
        $(`#unit-${id}`).val(unit);
    }

    function updateTemplateUnitAndPrice(id) {
        const selected = $(`#template-goods-select-${id}`).find(':selected');
        const unit = selected.data('unit') || '';
        $(`#template-unit-${id}`).val(unit);
    }

    function confirmDelete(orderId, orderCode) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            html: `Order <strong>${orderCode}</strong> akan dihapus permanen.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${orderId}`).submit();
            }
        });
    }

    function loadOrder(keyword = '', page = 1) {
        const customerId = {{ $customer->id }};

        fetch(`{{ route('order.data', ['customer_id' => $customer->id]) }}?keyword=${encodeURIComponent(keyword)}&page=${page}`)
            .then(response => response.json())
            .then(data => {
                console.log('DATA:', data);
                document.getElementById('orderTableBody').innerHTML = data.html;
                document.getElementById('pagination').innerHTML = data.pagination;

                // Pagination binding
                document.querySelectorAll('#pagination a').forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const url = new URL(this.href);
                        const page = url.searchParams.get('page');
                        const keyword = document.getElementById('searchOrder').value;
                        loadOrder(keyword, page);
                    });
                });

                // Zoom handler
                if (window.activeZoom) {
                    window.activeZoom.detach();
                }
                window.activeZoom = mediumZoom('.zoomable', {
                    margin: 24,
                    background: '#000'
                });
            });
    }

    document.addEventListener('DOMContentLoaded', () => loadOrder());

    document.getElementById('searchOrder').addEventListener('keyup', function () {
        loadOrder(this.value);
    });
</script>


@endsection