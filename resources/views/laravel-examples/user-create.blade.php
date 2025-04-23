@extends('layouts.user_type.auth')

@section('content')

<div>
    <form action="{{ route('user-management.store') }}" method="POST" role="form text-left"
        enctype="multipart/form-data">
        <div class="container-fluid">
            @csrf
            @method('PUT')
            <div class="container-fluid py-4">
                <div class="card">
                    <div class="card-header p-4">
                        <h6 class="mb-0">{{ __('Create User') }}</h6>
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
                                    <label for="user-name" class="form-control-label">{{ __('Full Name') }}</label>
                                    <div class="@error('user.name')border border-danger rounded-3 @enderror">
                                        <input class="form-control" type="text" placeholder="Full Name" id="user-name"
                                            name="name">
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
                                        <input class="form-control" type="email" placeholder="@example.com"
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
                                    <label for="user.phone" class="form-control-label">{{ __('Phone') }}</label>
                                    <div class="@error('user.phone')border border-danger rounded-3 @enderror">
                                        <input class="form-control" type="tel" placeholder="Phone Number" id="number"
                                            name="phone">
                                        @error('phone')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user.location" class="form-control-label">{{ __('Location') }}</label>
                                    <div class="@error('user.location') border border-danger rounded-3 @enderror">
                                        <input class="form-control" type="text" placeholder="Location" id="name"
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
                                            <option value="">Select Role</option>
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
                            <label for="about">{{ 'About' }}</label>
                            <div class="@error('user.about') border border-danger rounded-3 @enderror">
                                <textarea class="form-control" id="about" rows="3"
                                    placeholder="Say something about yourself" name="about_me"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="photo">{{ 'Photo' }}</label>
                            <div class="@error('user.photo') border border-danger rounded-3 @enderror">
                                <input type="file" class="form-control" id="imageInput" name="photo" accept="image/*">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password">Change Password</label>
                            <div class="input-group @error('password') border border-danger rounded-3 @enderror">
                                <input class="form-control" type="password" placeholder="Change Password" id="password"
                                    name="password">
                                <span class="input-group-text" onclick="togglePassword('password', this)">
                                    <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                </span>
                            </div>

                            <label for="password_confirmation" class="mt-3">Repeat Password</label>
                            <div class="input-group @error('password') border border-danger rounded-3 @enderror">
                                <input class="form-control" type="password" placeholder="Repeat Password"
                                    id="password_confirmation" name="password_confirmation">
                                <span class="input-group-text" onclick="togglePassword('password_confirmation', this)">
                                    <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                </span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit"
                                class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ 'Create User' }}</button>
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