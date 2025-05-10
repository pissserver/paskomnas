@extends('layouts.user_type.auth')

@section('title', 'Buat Orderan')

@section('content')

<div>
    <form action="{{ route('order.store', $customer->id) }}" method="POST" role="form text-left"
        enctype="multipart/form-data">
        <div class="container-fluid">
            @csrf
            <div class="container-fluid py-4">
                <div class="card">
                    <div class="card-header p-4 d-flex justify-content-between">
                        <div class="customer">
                            <h6 class="mb-2 btn btn-primary"><i class="fa fa-briefcase me-2"></i>{{ $customer->name }}
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
                        <img src="{{ asset($customer->photo) ?? asset('assets/img/default-profile.jpg') }}"
                            alt="{{ $customer->name }}" class="avatar shadow-lg img-fluid"
                            style="width: 80px; height: 80px; object-fit: cover;">
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
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="order-date">Tanggal Order</label>
                                    <input type="date" class="form-control" id="order-date" name="order_date" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="goods-name" class="form-control-label">Nama Barang Komoditi</label>
                                    <div class="@error('goods_id') border border-danger rounded-3 @enderror">
                                        <select class="form-control select2" id="goods-select" name="goods_id[]">
                                            <option value="">-- Pilih Barang Komoditi --</option>
                                            @foreach ($goods as $item)
                                            <option value="{{ $item->id }}" data-unit="{{ $item->unit }}"
                                                data-price="{{ $item->price }}">
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
                                    <label for="unit" class="form-control-label">{{ __('Satuan') }}</label>
                                    <div class="@error('unit') border border-danger rounded-3 @enderror">
                                        <input class="form-control" type="text" placeholder="Satuan" id="unit-0"
                                            name="unit[]" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="price" class="form-control-label">{{ __('Harga Satuan') }}</label>
                                    <div class="@error('price') border border-danger rounded-3 @enderror">
                                        <input class="form-control" type="text" placeholder="Harga Satuan" id="price-0"
                                            name="price[]" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
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
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    let orderItemCount = 0;

    // Inisialisasi Select2 saat dokumen siap
    $(document).ready(function () {
        $('.select2').select2({
            theme: 'bootstrap-5',
            placeholder: "-- Pilih Barang Komoditi --",
            allowClear: true,
            width: '100%'
        });

        // Untuk item pertama (id = 0)
        $('#goods-select').on('change', function () {
            const selected = $(this).find(':selected');
            const unit = selected.data('unit') || '';
            const price = selected.data('price') || '';
            $('#unit-0').val(unit);
            $('#price-0').val(price);
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
                    <option value="{{ $item->id }}" data-unit="{{ $item->unit }}" data-price="{{ $item->price }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label>Satuan</label>
                <input type="text" class="form-control" placeholder="Satuan" id="unit-${orderItemCount}" name="unit[]" disabled>
            </div>
            <div class="col-md-3">
                <label>Harga Satuan</label>
                <input type="text" class="form-control" placeholder="Harga Satuan" id="price-${orderItemCount}" name="price[]" disabled>
            </div>
            <div class="col-md-2">
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
    }

    // Fungsi hapus baris orderan
    function removeOrderItem(id) {
        $(`#order-item-${id}`).remove();
    }

    // Fungsi update satuan dan harga berdasarkan barang yang dipilih
    function updateUnitAndPrice(id) {
        const selected = $(`#goods-select-${id}`).find(':selected');
        const unit = selected.data('unit') || '';
        const price = selected.data('price') || '';
        $(`#unit-${id}`).val(unit);
        $(`#price-${id}`).val(price);
    }
</script>


@endsection