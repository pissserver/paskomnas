@extends('layouts.user_type.auth')

@section('title', 'Tambah Pengguna')

@section('content')

<div>
    <form action="{{ route('user-management.store') }}" method="POST" role="form text-left"
        enctype="multipart/form-data">
        <div class="container-fluid">
            @csrf
            @method('PUT')
            <div class="container-fluid py-4">
                <div class="card">
                    <div class="card-header p-4 d-flex justify-content-between">
                        <h6 class="mb-0">{{ __('Tambahkan Pengguna') }}</h6>
                        <a href="{{ route('user-management.index') }}"
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
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user-name" class="form-control-label">{{ __('Nama Lengkap') }}</label>
                                    <div class="@error('user.name')border border-danger rounded-3 @enderror">
                                        <input class="form-control" type="text" placeholder="Nama Lengkap"
                                            id="user-name" name="name">
                                        @error('name')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user-email" class="form-control-label">{{ __('Email') }}</label>
                                    <div class="@error('email')border border-danger rounded-3 @enderror">
                                        <input class="form-control" type="email" placeholder="@contoh.com"
                                            id="user-email" name="email">
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
                                    <label for="user.phone" class="form-control-label">{{ __('No Telepon') }}</label>
                                    <div class="@error('user.phone')border border-danger rounded-3 @enderror">
                                        <input class="form-control" type="tel" placeholder="No Telepon" id="number"
                                            name="phone">
                                        @error('phone')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user.location" class="form-control-label">{{ __('Lokasi') }}</label>
                                    <div class="@error('user.location') border border-danger rounded-3 @enderror">
                                        <input class="form-control" type="text" placeholder="Lokasi" id="name"
                                            name="location">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role" class="form-control-label">{{ __('Role') }}</label>
                                    <div class="@error('role') border border-danger rounded-3 @enderror">
                                        <select class="form-control" id="role" name="role">
                                            <option value="">Pilih Role</option>
                                            <option value="master">Master</option>
                                            <option value="user">User</option>
                                        </select>
                                        @error('role')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="about">{{ 'Tentang Saya' }}</label>
                            <div class="@error('user.about') border border-danger rounded-3 @enderror">
                                <textarea class="form-control" id="about" rows="3"
                                    placeholder="Ceritakan sedikit tentang diri anda" name="about_me"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="photo">{{ 'Foto' }}</label>
                            <div class="@error('user.photo') border border-danger rounded-3 @enderror">
                                <div class="d-flex align-items-center">
                                    <div id="imagePreview" class="zoomable"
                                        style="border: 2px dashed #DE0A93; border-radius: 8px; width: 43px; height: 43px; background-color: #ffffff; background-position: center; background-size: cover; background-repeat: no-repeat;">
                                    </div>

                                    <input type="file" id="imageInput" name="photo" accept="image/*" class="d-none"
                                        onchange="previewSelectedImage(event)">

                                    <div class="flex-grow-1 ms-3 pt-3">
                                        <a href="javascript:;" onclick="document.getElementById('imageInput').click()"
                                            class="btn bg-gradient-primary w-100">
                                            <i class="fa fa-images pe-2" data-bs-toggle="tooltip"
                                                title="Upload Image"></i>Unggah Foto
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-group @error('password') border border-danger rounded-3 @enderror">
                                <input class="form-control" type="password" placeholder="Password" id="password"
                                    name="password">
                                <span class="input-group-text" onclick="togglePassword('password', this)">
                                    <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                </span>
                            </div>

                            <label for="password_confirmation" class="mt-3">Ulangi Password</label>
                            <div class="input-group @error('password') border border-danger rounded-3 @enderror">
                                <input class="form-control" type="password" placeholder="Ulangi Password"
                                    id="password_confirmation" name="password_confirmation">
                                <span class="input-group-text" onclick="togglePassword('password_confirmation', this)">
                                    <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                </span>
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

    function togglePassword(inputId, iconElement) {
        const input = document.getElementById(inputId);
        const icon = iconElement.querySelector('i');

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            input.type = "password";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    }
</script>
@endsection