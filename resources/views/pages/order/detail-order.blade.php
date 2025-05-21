@extends('layouts.user_type.auth')

@section('title', 'Detail Orderan')

@section('content')

<div>
    <div class="row">
        <div class="col-md-3 py-4">
            <div class="card">
                <div class="card-header mx-4 p-3 text-center">
                    <img src="{{ asset($order->customer->photo) ?? asset('assets/img/default-profile.jpg') }}"
                        alt="{{ $order->customer->name }}" class="avatar shadow-lg img-fluid"
                        style="width: 80px; height: 80px; object-fit: cover;">
                </div>
                <div class="card-body pt-0 p-3 text-center">
                    <h6 class="text-center mb-0">{{ $order->customer->name }}</h6>
                    <span class="text-xs">{{ $order->customer->address }}</span>
                    <hr class="horizontal dark my-3">
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('order.create', $order->customer->id) }}" class="btn  btn-primary">
                            <i class="fas fa-shopping-cart me-2"></i> Order
                        </a>
                        <a href="{{ route('customer.edit', $order->customer->id) }}" class="btn  btn-info">
                            <i class="fas fa-info-circle me-2"></i> Detail
                        </a>
                    </div>
                </div>
            </div>
            <div class="card mt-3">
                <div class="col-12 pt-3 pb-2">
                    <div class="nav-wrapper position-relative d-flex justify-content-center">
                        <ul class="nav nav-pills bg-transparent" role="tablist">
                            <!-- Detail -->
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active mb-2 me-2" id="detail-tab" data-bs-toggle="tab" href="#detail"
                                    role="tab" aria-controls="detail" aria-selected="true">
                                    <i class="fas fa-info-circle me-1 text-primary"></i> Detail
                                </a>
                            </li>

                            <!-- Laporan -->
                            <li class="nav-item" role="presentation">
                                <a class="nav-link mb-2" id="report-tab" data-bs-toggle="tab" href="#report" role="tab"
                                    aria-controls="report" aria-selected="false">
                                    <i class="fas fa-file-alt me-1 text-primary"></i> Laporan
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="detail" role="tabpanel" aria-labelledby="detail">
                    <form
                        action="{{ route('order.update', ['customer_id' => $order->customer_id, 'order_id' => $order->id]) }}"
                        method="POST" role="form text-left" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class=" py-4">
                            <div class="card">
                                <div class="card-header p-4 d-flex justify-content-between">
                                    <div class="order">
                                        <h6 class="mb-3 btn bg-gradient-primary"><i
                                                class="fa fa-shopping-cart me-2"></i>{{ $order->order_code }}
                                        </h6>
                                        <p class="text-sm d-flex align-items-center mb-2">
                                            <i class="fa fa-calendar me-2" style="width: 20px; text-align: center;"></i>
                                            {{ \Carbon\Carbon::parse($order->order_date)->translatedFormat('l, d F Y') }}
                                        </p>
                                        <p class="text-sm d-flex align-items-center mb-1">
                                            @if ($order->status === 'confirmed')
                                            <span class="text-white bg-primary rounded-3 py-1 px-2">
                                                <i class="fas fa-sync-alt me-2"></i> Diproses
                                            </span>
                                            @elseif ($order->status === 'done')
                                            <span class="text-white bg-success rounded-3 py-1 px-2">
                                                <i class="fas fa-check-circle me-2"></i> Selesai
                                            </span>
                                            @elseif ($order->status === 'cancelled')
                                            <span class="text-white bg-danger rounded-3 py-1 px-2">
                                                <i class="fas fa-times-circle me-2"></i> Dibatalkan
                                            </span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="card-body pt-2 p-4">
                                    @if($errors->any())
                                    <div class="alert alert-primary alert-dismissible fade show" role="alert">
                                        <span class="alert-text text-white">
                                            {{$errors->first()}}</span>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close">
                                            <i class="fa fa-close" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    @endif
                                    @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" id="alert-success"
                                        role="alert">
                                        <span class="alert-text text-white">
                                            {{ session('success') }}</span>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close">
                                            <i class="fa fa-close" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="order-date">Tanggal Order</label>
                                                <input type="date" class="form-control" id="order-date"
                                                    name="order_date" value="{{ $order->order_date }}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div id="order-items-wrapper">
                                            @foreach($order->orderItems as $index => $orderItem)
                                            <div class="order-item row mb-2" id="order-item-{{ $index }}">
                                                <input type="hidden" name="order_item_id[]"
                                                    value="{{ $orderItem->id }}">

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Nama Barang Komoditi</label>
                                                        <select class="form-control select2"
                                                            id="goods-select-{{ $index }}" name="goods_id[]"
                                                            onchange="updateUnitAndPrice({{ $index }})">
                                                            <option value="">-- Pilih Barang Komoditi --</option>
                                                            @foreach ($goods as $item)
                                                            <option value="{{ $item->id }}"
                                                                data-unit="{{ $item->unit }}"
                                                                {{ $orderItem->goods_id == $item->id ? 'selected' : '' }}>
                                                                {{ $item->name }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label>Satuan</label>
                                                    <input type="text" class="form-control" id="unit-{{ $index }}"
                                                        value="{{ $orderItem->goods->unit }}" disabled>
                                                </div>

                                                <div class="col-md-3">
                                                    <label>Harga Satuan</label>
                                                    <input type="number" step="1000" class="form-control" name="price[]"
                                                        value="{{ $orderItem->price }}">
                                                </div>

                                                <div class="col-md-2">
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
                                        <div id="order-items-container"></div>

                                        <div class="d-flex justify-content-end mb-4">
                                            <button type="button" class="btn btn-outline-primary me-2"
                                                onclick="addOrderItem()">
                                                <i class="fa fa-plus"></i> Tambah Barang
                                            </button>
                                        </div>

                                        <div class="d-flex justify-content-end gap-2">
                                            <button type="submit" class="btn bg-gradient-dark">
                                                Simpan<i class="fa fa-save ms-2"></i>
                                            </button>
                                            <a href="{{ route('order.create', $order->customer->id) }}"
                                                class="btn bg-gradient-danger">
                                                Kembali<i class="fa fa-arrow-right ms-2"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="tab-pane fade" id="report" role="tabpanel" aria-labelledby="report">
                    <div class="py-4">
                        <div class="card">
                            <div class="card-header p-4 d-flex justify-content-between">
                                <div class="order">
                                    <h6 class="mb-3 btn bg-gradient-primary"><i
                                            class="fa fa-shopping-cart me-2"></i>{{ $order->order_code }}
                                    </h6>
                                    <p class="text-sm d-flex align-items-center mb-2">
                                        <i class="fa fa-calendar me-2" style="width: 20px; text-align: center;"></i>
                                        {{ \Carbon\Carbon::parse($order->order_date)->translatedFormat('l, d F Y') }}
                                    </p>
                                    <p class="text-sm d-flex align-items-center mb-1">
                                        @if ($order->status === 'confirmed')
                                        <span class="text-white bg-primary rounded-3 py-1 px-2">
                                            <i class="fas fa-sync-alt me-2"></i> Diproses
                                        </span>
                                        @elseif ($order->status === 'done')
                                        <span class="text-white bg-success rounded-3 py-1 px-2">
                                            <i class="fas fa-check-circle me-2"></i> Selesai
                                        </span>
                                        @elseif ($order->status === 'cancelled')
                                        <span class="text-white bg-danger rounded-3 py-1 px-2">
                                            <i class="fas fa-times-circle me-2"></i> Dibatalkan
                                        </span>
                                        @endif
                                    </p>
                                </div>
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
                                <div class="alert alert-success alert-dismissible fade show" id="alert-success"
                                    role="alert">
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
                                            <input type="date" class="form-control" id="order-date" name="order_date"
                                                value="{{ $order->order_date }}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="table-responsive p-0">
                                        <table class="table align-items-center mb-0 table-hover table-striped">
                                            <thead>
                                                <tr>
                                                    <th
                                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        Nama Barang
                                                    </th>
                                                    <th
                                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                        Spesifikasi
                                                    </th>
                                                    <th
                                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        Satuan
                                                    </th>
                                                    <th
                                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        Harga
                                                    </th>
                                                    <th
                                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        Jumlah
                                                    </th>
                                                    <th
                                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        Total
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="customerTableBody">
                                                @foreach($order->orderItems as $index => $orderItem)

                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let orderItemCount = {{ count($order->orderItems) }}; // mulai dari jumlah item awal

    $(document).ready(function () {
        // Inisialisasi semua select2
        $('.select2').select2({
            theme: 'bootstrap-5',
            placeholder: "-- Pilih Barang Komoditi --",
            allowClear: true,
            width: '100%'
        });

        // Update unit dan price untuk semua order item yang sudah ada
        @foreach ($order->orderItems as $index => $orderItem)
            updateUnitAndPrice({{ $index }});
        @endforeach
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

    function removeOrderItem(id) {
        $(`#order-item-${id}`).remove();
    }

    function addOrderItem() {
        orderItemCount++;
        const newItem = `
        <div class="order-item row mb-2" id="order-item-${orderItemCount}">
            <input type="hidden" name="order_item_id[]" value="">

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
                <input type="text" class="form-control" placeholder="Satuan" id="unit-${orderItemCount}" disabled>
            </div>

            <div class="col-md-3">
                <label>Harga Satuan</label>
                <input type="number" step="1000" name="price[]" class="form-control" placeholder="Harga Satuan">
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

    function updateUnitAndPrice(id) {
        const selected = $(`#goods-select-${id}`).find(':selected');
        const unit = selected.data('unit') || '';
        $(`#unit-${id}`).val(unit);
        $(`#price-${id}`).val(price);
    }
</script>


@endsection