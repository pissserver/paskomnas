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
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customer-name"
                                        class="form-control-label">{{ __('Nama Customer') }}</label>
                                    <div class="@error('customer.name')border border-danger rounded-3 @enderror">
                                        <input class="form-control" type="text" placeholder="Nama Customer"
                                            id="customer-name" name="name" value="{{ $customer->name }}">
                                        @error('name')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pic-receiver" class="form-control-label">{{ __('PIC') }}</label>
                                    <div class="@error('pic_receiver') border border-danger rounded-3 @enderror">
                                        <input class="form-control" type="number" placeholder="PIC" id="pic-receiver"
                                            name="pic_receiver" value="{{ $customer->pic_receiver }}">
                                        @error('pic_receiver')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="npwp" class="form-control-label">{{ __('NPWP') }}</label>
                                    <div class="@error('npwp') border border-danger rounded-3 @enderror">
                                        <input class="form-control" type="text" placeholder="xx.xxx.xxx.x-xxx.xxx"
                                            id="npwp" name="npwp" value="{{ $customer->npwp }}">
                                        @error('npwp')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="about">{{ 'Alamat Perusahaan' }}</label>
                            <div class="@error('adress') border border-danger rounded-3 @enderror">
                                <textarea class="form-control" id="about" rows="3" placeholder="Alamat Perusahaan"
                                    name="address">{{ $customer->address }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="about">{{ 'Alamat Delivery' }}</label>
                            <div class="@error('delivery_address') border border-danger rounded-3 @enderror">
                                <textarea class="form-control" id="about" rows="3" placeholder="Alamat Delivery"
                                    name="delivery_address">{{ $customer->delivery_address }}</textarea>
                            </div>
                        </div>
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
                                        <input class="form-control" type="date" id="start-date" name="start_date"
                                            value="{{ old('start_date', optional($periode)->start_date) }}">
                                        @error('start_date')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="end-date" class="form-control-label">{{ __('Tanggal Akhir') }}</label>
                                    <div class="@error('end_date') border border-danger rounded-3 @enderror">
                                        <input class="form-control" type="date" id="end-date" name="end_date"
                                            value="{{ old('end_date', optional($periode)->end_date) }}">
                                        @error('end_date')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
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
                                    <input class="form-control" type="text" id="goods-name" value="{{ $item->name }}"
                                        disabled>

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
</script>

@endsection