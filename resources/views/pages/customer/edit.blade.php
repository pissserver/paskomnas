@extends('layouts.user_type.auth')

@section('title', 'Edit Customer')

@section('content')

<div>
    <form action="{{ route('customer.update', $customer->id) }}" method="POST" role="form text-left"
        enctype="multipart/form-data">
        <div class="container-fluid">
            @csrf
            @method('PUT')
            <div class="container-fluid py-4">
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
                                class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ 'Simpan' }}&nbsp;&nbsp;<i
                                    class="fa fa-save"></i></button>
                        </div>
                    </div>
                </div>
            </div>
    </form>
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