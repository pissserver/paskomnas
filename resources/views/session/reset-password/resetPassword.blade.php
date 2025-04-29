@extends('layouts.user_type.guest')

@section('content')

<div class="page-header section-height-75">
    <div class="container">
        <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
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
                        {!! session('success') !!}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <i class="fa fa-close" aria-hidden="true"></i>
                    </button>
                </div>
                @endif

                <div class="card card-plain mt-8">
                    <div class="card-header pb-0 text-left bg-transparent">
                        <h4 class="mb-0">Ganti password</h4>
                    </div>
                    <div class="card-body">
                        <form role="form" action="{{ route('password.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div>
                                <label for="email">Email</label>
                                <div class="">
                                    <input id="email" name="email" type="email" class="form-control" placeholder="Email"
                                        value="{{ $email }}" aria-label="Email" aria-describedby="email-addon" readonly>
                                    @error('email')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="mt-2">
                                <label for="password">Password Baru</label>
                                <div class="input-group @error('password') border border-danger rounded-3 @enderror">
                                    <input type="password" class="form-control" placeholder="Password" name="password"
                                        id="password" aria-label="Password" aria-describedby="password-addon">
                                    <span class="input-group-text" onclick="togglePassword('password', this)">
                                        <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                    </span>
                                </div>
                                @error('password')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mt-2">
                                <label for="password_confirmation">Konfirmasi Password</label>
                                <div class="input-group @error('password') border border-danger rounded-3 @enderror">
                                    <input class="form-control" type="password" placeholder="Ulangi Password"
                                        id="password_confirmation" name="password_confirmation">
                                    <span class="input-group-text"
                                        onclick="togglePassword('password_confirmation', this)">
                                        <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                    </span>
                                </div>
                                @error('password')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                                <div class="text-center">
                                    <button type="submit" class="btn bg-gradient-info w-100 mt-4 mb-0">Perbarui
                                        Password <i class="fas fa-key ms-2"></i></button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
                    <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6"
                        style="background-image:url({{ asset('assets/img/reset-password-background.jpg') }})"></div>
                    <span class="mask bg-gradient-dark opacity-5"></span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

<script>
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