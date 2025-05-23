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
                        <li class="nav-item">
                            <a class="nav-link mb-0 px-0 py-1 " id="order-tab" data-bs-toggle="tab" href="#order"
                                role="tab" aria-controls="order" aria-selected="true">
                                <i class="fas fa-shopping-cart me-1"></i>
                                <span class="ms-1">{{ __('Data Order') }}</span>
                            </a>
                        </li>
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
            <form action="{{ route('customer.update', $customer->id) }}" method="POST" role="form text-left"
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
                        @foreach($goods as $item)
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="goods-name"
                                        class="form-control-label">{{ __('Barang Komoditi') }}</label>
                                    <div class="@error('goods_name')border border-danger rounded-3 @enderror">
                                        <input class="form-control" type="text" id="goods-name" name="goods_name"
                                            value="{{ $item->name }}" disabled>
                                        @error('goods_name')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
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
                                    <div class="@error('nominal') border border-danger rounded-3 @enderror">
                                        <input class="form-control" type="number" step="1000" placeholder="0"
                                            id="goods-price" name="nominal" value="">
                                        @error('nominal')
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

<script>
    function previewSelectedImage(event) {
        const input = event.target;
        const preview = document.getElementById('imagePreview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.style.backgroundImage = `url('${e.target.result}')`;
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.backgroundImage = '';
        }
    }
</script>
@endsection