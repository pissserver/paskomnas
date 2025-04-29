@extends('layouts.user_type.guest')

@section('content')

<section class="min-vh-100 mb-8">
  <div class="page-header align-items-start min-vh-50 pt-5 pb-11 mx-3 border-radius-lg"
    style="background-image:url({{ asset('assets/img/register-background.jpg') }})">
    <span class="mask bg-gradient-dark opacity-6"></span>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-5 text-center mx-auto">
          <h1 class="text-white mb-2 mt-7">Selamat Datang!</h1>
          <p class="text-lead text-white">Daftar akun anda untuk mendapatkan akses penuh ke sistem.</p>
        </div>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row mt-lg-n10 mt-md-n11 mt-n10">
      <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
        <div class="card z-index-0">
          <div class="card-header text-center pt-4">
            <h5>Daftar dengan</h5>
          </div>
          <div class="row px-xl-5 px-sm-4 px-3">
            <div class="col-12 me-auto px-1">
              <a class="btn btn-outline-light w-100" href={{ route('google.auth') }}>
                <svg width="24px" height="32px" viewBox="0 0 64 64" version="1.1" xmlns="http://www.w3.org/2000/svg"
                  xmlns:xlink="http://www.w3.org/1999/xlink">
                  <g id="Artboard" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <g id="google-icon" transform="translate(3.000000, 2.000000)" fill-rule="nonzero">
                      <path
                        d="M57.8123233,30.1515267 C57.8123233,27.7263183 57.6155321,25.9565533 57.1896408,24.1212666 L29.4960833,24.1212666 L29.4960833,35.0674653 L45.7515771,35.0674653 C45.4239683,37.7877475 43.6542033,41.8844383 39.7213169,44.6372555 L39.6661883,45.0037254 L48.4223791,51.7870338 L49.0290201,51.8475849 C54.6004021,46.7020943 57.8123233,39.1313952 57.8123233,30.1515267"
                        id="Path" fill="#4285F4"></path>
                      <path
                        d="M29.4960833,58.9921667 C37.4599129,58.9921667 44.1456164,56.3701671 49.0290201,51.8475849 L39.7213169,44.6372555 C37.2305867,46.3742596 33.887622,47.5868638 29.4960833,47.5868638 C21.6960582,47.5868638 15.0758763,42.4415991 12.7159637,35.3297782 L12.3700541,35.3591501 L3.26524241,42.4054492 L3.14617358,42.736447 C7.9965904,52.3717589 17.959737,58.9921667 29.4960833,58.9921667"
                        id="Path" fill="#34A853"></path>
                      <path
                        d="M12.7159637,35.3297782 C12.0932812,33.4944915 11.7329116,31.5279353 11.7329116,29.4960833 C11.7329116,27.4640054 12.0932812,25.4976752 12.6832029,23.6623884 L12.6667095,23.2715173 L3.44779955,16.1120237 L3.14617358,16.2554937 C1.14708246,20.2539019 0,24.7439491 0,29.4960833 C0,34.2482175 1.14708246,38.7380388 3.14617358,42.736447 L12.7159637,35.3297782"
                        id="Path" fill="#FBBC05"></path>
                      <path
                        d="M29.4960833,11.4050769 C35.0347044,11.4050769 38.7707997,13.7975244 40.9011602,15.7968415 L49.2255853,7.66898166 C44.1130815,2.91684746 37.4599129,0 29.4960833,0 C17.959737,0 7.9965904,6.62018183 3.14617358,16.2554937 L12.6832029,23.6623884 C15.0758763,16.5505675 21.6960582,11.4050769 29.4960833,11.4050769"
                        id="Path" fill="#EB4335"></path>
                    </g>
                  </g>
                </svg>
              </a>
            </div>
            <div class="mt-2 position-relative text-center">
              <p class="text-sm font-weight-bold mb-2 text-secondary text-border d-inline z-index-2 bg-white px-3">
                Atau
              </p>
            </div>
          </div>
          <div class="card-body">
            <form role="form text-left" method="POST" action="{{ route('register.store') }}">
              @csrf
              <div class="mb-3">
                <input type="text" class="form-control" placeholder="Nama Lengkap" name="name" id="name"
                  aria-label="Name" aria-describedby="name" value="{{ old('name') }}">
                @error('name')
                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                @enderror
              </div>
              <div class="mb-3">
                <input type="email" class="form-control" placeholder="Email" name="email" id="email" aria-label="Email"
                  aria-describedby="email-addon" value="{{ old('email') }}">
                @error('email')
                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                @enderror
              </div>
              {{-- <div class="mb-3">
                <input type="password" class="form-control" placeholder="Password" name="password" id="password"
                  aria-label="Password" aria-describedby="password-addon">
                @error('password')
                <p class="text-danger text-xs mt-2">{{ $message }}</p>
              @enderror
          </div> --}}
          <div class="mb-3">
            <div class="input-group @error('password') border border-danger rounded-3 @enderror">
              <input type="password" class="form-control" placeholder="Password" name="password" id="password"
                aria-label="Password" aria-describedby="password-addon">
              <span class="input-group-text" onclick="togglePassword('password', this)">
                <i class="fa fa-eye-slash" aria-hidden="true"></i>
              </span>
            </div>
            @error('password')
            <p class="text-danger text-xs mt-2">{{ $message }}</p>
            @enderror
          </div>

          <div class="mb-3">
            <div class="input-group @error('password') border border-danger rounded-3 @enderror">
              <input class="form-control" type="password" placeholder="Ulangi Password" id="password_confirmation"
                name="password_confirmation">
              <span class="input-group-text" onclick="togglePassword('password_confirmation', this)">
                <i class="fa fa-eye-slash" aria-hidden="true"></i>
              </span>
            </div>
            @error('password')
            <p class="text-danger text-xs mt-2">{{ $message }}</p>
            @enderror
          </div>
          <div class="form-check form-check-info text-left">
            <input class="form-check-input" type="checkbox" name="agreement" id="flexCheckDefault"
              onchange="toggleSubmitButton()">
            <label class="form-check-label pt-1" for="flexCheckDefault">
              Saya setuju dengan <a href="javascript:;" class="text-dark font-weight-bolder">Syarat dan Ketentuan</a>
            </label>
            {{-- @error('agreement')
            <p class="text-danger text-xs mt-2">First, agree to the Terms and Conditions, then try register again.
            </p>
            @enderror --}}
          </div>
          <div class="text-center">
            <button type="submit" id="submitButton" class="btn bg-gradient-dark w-100 my-4 mb-2 opacity-50"
              disabled>Daftar <i class="fa fa-user-plus ms-2"></i></button>
          </div>
          <p class="text-sm mt-3 mb-0">Sudah mempunyai akun? <a href="login"
              class="text-dark font-weight-bolder">Login</a></p>
          </form>
        </div>
      </div>
    </div>
  </div>
  </div>
</section>

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

    function toggleSubmitButton() {
        const checkbox = document.getElementById('flexCheckDefault');
        const submitButton = document.getElementById('submitButton');

        if (checkbox.checked) {
            submitButton.removeAttribute('disabled');
        submitButton.classList.remove('opacity-50');
        } else {
            submitButton.setAttribute('disabled', 'true');
            submitButton.classList.add('opacity-50');
        }
    }
</script>