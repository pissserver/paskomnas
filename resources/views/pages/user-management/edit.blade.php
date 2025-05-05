@extends('layouts.user_type.auth')

@section('title', 'Edit Pengguna')

@section('content')

<div>
    <form action="{{ route('user-management.update', $user->id) }}" method="POST" role="form text-left"
        enctype="multipart/form-data">
        <div class="container-fluid">
            @csrf
            @method('PUT')
            <div class="page-header min-height-300 border-radius-xl mt-4"
                style="background-image: url('{{ asset('assets/img/bg-user-edit.png') }}'); background-position-y: 50%;">
                <span class="mask bg-gradient-dark opacity-2"></span>
            </div>
            <div class="card card-body blur shadow-blur mx-4 mt-n6">
                <div class="row gx-4">
                    <div class="col-auto">
                        <div class="avatar avatar-xl position-relative">
                            <img id="previewImage"
                                src="{{ $user->photo ? asset($user->photo) : asset('assets/img/default-profile.jpg') }}"
                                alt="Preview" class="w-100 border-radius-lg shadow-sm">
                            <input type="file" id="imageInput" name="photo" accept="image/*" class="d-none"
                                onchange="previewSelectedImage(event)">
                            <a href="javascript:;" onclick="document.getElementById('imageInput').click()"
                                class="btn btn-sm btn-icon-only bg-gradient-light position-absolute bottom-0 end-0 mb-n2 me-n2">
                                <i class="fa fa-pen top-0" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="Ganti Foto"></i>
                            </a>
                            <input type="hidden" name="reset_photo" id="resetPhoto" value="0">
                            <a href="javascript:;" onclick="resetImageToDefault()"
                                class="btn btn-sm btn-icon-only bg-gradient-danger position-absolute bottom-0 start-0 mb-n2 ms-n2">
                                <i class="fa fa-trash top-0" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="Hapus Foto"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-auto my-auto">
                        <div class="h-100">
                            <h5 class="mb-1">
                                {{ $user->name }}
                            </h5>
                            <p
                                class="mb-0 font-weight-bold text-sm text-uppercas btn {{ $user->role == 'master' ? "bg-gradient-primary" : "bg-gradient-warning" }} btn-sm">
                                {{ $user->role }}
                            </p>
                        </div>
                    </div>
                    {{-- s --}}
                </div>
            </div>
        </div>
        <div class="container-fluid py-4">
            <div class="card">
                <div class="card-header pb-0 p-4 d-flex justify-content-between">
                    <h6 class="mb-0">{{ __('Informasi Pengguna') }}</h6>

                    <a href="{{ route('user-management.index') }}"
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
                                <label for="user-name" class="form-control-label">{{ __('Nama Lengkap') }}</label>
                                <div class="@error('user.name')border border-danger rounded-3 @enderror">
                                    <input class="form-control" value="{{ $user->name }}" type="text"
                                        placeholder="Nama Lengkap" id="user-name" name="name">
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
                                    <input class="form-control" value="{{ $user->email }}" type="email"
                                        placeholder="@contoh.com" id="user-email" name="email">
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
                                        name="phone" value="{{ $user->phone }}">
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
                                        name="location" value="{{ $user->location }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="role" class="form-control-label">{{ __('Role') }}</label>
                                <div class="@error('role') border border-danger rounded-3 @enderror">
                                    <select class="form-control" id="role" name="role" required>
                                        <option value="">Pilih Role</option>
                                        <option value="master" {{ $user->role == 'master' ? 'selected' : '' }}>
                                            Master</option>
                                        <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User
                                        </option>
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
                        <div class="@error('user.about')border border-danger rounded-3 @enderror">
                            <textarea class="form-control" id="about" rows="3"
                                placeholder="Ceritakan sedikit tentang diri anda"
                                name="about_me">{{ $user->about_me }}</textarea>
                        </div>
                    </div>
                    @if ($user->google_id == NULL)
                    <div class="form-group">
                        <label for="password">Ganti Password</label>
                        <div class="input-group @error('password') border border-danger rounded-3 @enderror">
                            <input class="form-control" type="password" placeholder="Ganti Password" id="password"
                                name="password">
                            <span class="input-group-text" onclick="togglePassword('password', this)">
                                <i class="fa fa-eye-slash" aria-hidden="true"></i>
                            </span>
                        </div>

                        <label for="password_confirmation" class="mt-3">Ulangi Password</label>
                        <div class="input-group @error('password') border border-danger rounded-3 @enderror">
                            <input class="form-control" type="password" placeholder="Ulangis Password"
                                id="password_confirmation" name="password_confirmation">
                            <span class="input-group-text" onclick="togglePassword('password_confirmation', this)">
                                <i class="fa fa-eye-slash" aria-hidden="true"></i>
                            </span>
                        </div>
                    </div>
                    @endif

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ 'Simpan' }}&nbsp;&nbsp;<i
                                class="fa fa-save"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function previewSelectedImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImage').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }

    function resetImageToDefault() {
        const preview = document.getElementById('previewImage');
        preview.src = "{{ asset('assets/img/default-profile.jpg') }}";
        document.getElementById('resetPhoto').value = 1;
        document.getElementById('imageInput').value = '';
    }

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