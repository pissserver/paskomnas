<!-- Navbar -->
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
    data-scroll="true">
    <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm">
                    <a class="opacity-5 text-dark" href="javascript:;">Halaman</a>
                </li>
                @foreach (explode('/', Request::path()) as $segment)
                <li class="breadcrumb-item text-sm text-dark active text-capitalize" aria-current="page">
                    {{ ucwords(str_replace('-', ' ', $segment)) }}
                </li>
                @endforeach
            </ol>
            <h6 class="font-weight-bolder mb-0 text-capitalize">
                {{ ucwords(str_replace('-', ' ', str_replace('/', ' / ', Request::path()))) }}
            </h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4 d-flex justify-content-end" id="navbar">
            <div class="card blur shadow-blur pe-2 me-3">
                <div class="d-flex align-items-center">
                    <div class="avatar">
                        <img src="{{ Auth::user()->photo ? asset(Auth::user()->photo) : asset('assets/img/default-profile.jpg') }}"
                            alt="..." class="border-radius-lg shadow-sm"
                            style="width: 28px; height: 28px; object-fit: cover;">
                    </div>
                    <div>
                        <h6 class="mb-0" style="font-size: 0.75rem; font-weight: 600;">
                            {{ auth()->user()->name }}
                        </h6>
                        <p class="mb-0" style="margin-top: -5px; font-size: 0.65rem; color: #6c757d;">
                            {{ auth()->user()->email }}
                        </p>
                    </div>
                </div>
            </div>

            <ul class="navbar-nav justify-content-end">
                <li class="nav-item d-flex align-items-center">
                    <div class="nav-item d-flex align-self-end">
                        <a href="{{ route('chat.index') }}"
                            class="btn btn-dark active mb-0 text-white position-relative">
                            <i class="fas fa-comments fa-lg"></i>
                            <span id="chat-unread-badge"
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none">
                                0
                            </span>
                        </a>
                    </div>
                </li>
                <li class="nav-item dropdown d-flex align-items-center ps-2">
                    <a href="javascript:;" class="btn btn-secondary active mb-0 text-white" id="dropdownMenu"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-exchange-alt me-sm-1"></i>
                        <span class="d-sm-inline d-none">Beralih Akun</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-3"
                        style="max-height: 300px; overflow-y: auto;" aria-labelledby="dropdownMenu">
                        <div class="google-switch ps-2 pe-2">
                            <a class="btn btn-white btn-outline-light w-100" href={{ route('google.auth') }}>
                                <svg width="24px" height="32px" viewBox="0 0 64 64" version="1.1"
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <g id="Artboard" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <g id="google-icon" transform="translate(3.000000, 2.000000)"
                                            fill-rule="nonzero">
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
                            <div class="position-relative text-center mb-2">
                                <p class="text-sm font-weight-bold text-secondary text-border d-inline z-index-2">
                                    Atau
                                </p>
                            </div>
                        </div>
                        @foreach ($users as $user)
                        <li class="mb-2">
                            <a class="dropdown-item border-radius-md" href="javascript:;"
                                onclick="{{ $user->id != Auth::user()->id ? "confirmSwitch($user->id, '" . ($user->photo ? asset($user->photo) : asset('assets/img/default-profile.jpg')) . "', '$user->name', '$user->email', '$user->role')" : '' }}">
                                <div class="d-flex py-1">
                                    <div class="my-auto position-relative">
                                        <img src="{{ $user->photo ? asset($user->photo) : asset('assets/img/default-profile.jpg') }}"
                                            alt="..."
                                            class="avatar avatar-sm me-3 rounded-circle border {{ $user->role == 'master' ? "border-primary" : "border-warning" }} border-3"
                                            style="width: 28px; height: 28px; object-fit: cover; border-radius: 50%;">

                                        <div style="
                                            position: absolute;
                                            bottom: -4px;
                                            right: 13px;
                                            background-color: white;
                                            border: 1px solid #ccc;
                                            border-radius: 50%;
                                            width: 16px;
                                            height: 16px;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                            box-shadow: 0 0 2px rgba(0,0,0,0.2);
                                        ">
                                            <i class="fa fa-user }}" style="font-size: 9px; color: #6c757d;"></i>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm font-weight-normal mb-1">
                                            {{ $user->name }}
                                        </h6>
                                        <p class="text-xs text-secondary mb-1">
                                            {{ $user->email }}
                                        </p>
                                        <div
                                            class="d-flex align-items-center text-xs {{ $user->id == Auth::user()->id ? 'text-success' : 'text-danger' }}">
                                            {{ $user->id == Auth::user()->id ? 'Masuk' : 'Keluar' }}
                                            <div style="
                                                {{ $user->id == Auth::user()->id ? 'background-color: green;' : 'background-color: red;' }}
                                                border-radius: 50%;
                                                width: 16px;
                                                height: 16px;
                                                display: flex;
                                                align-items: center;
                                                justify-content: center;
                                                box-shadow: 0 0 2px rgba(0,0,0,0.2);
                                                margin-left: 5px;
                                            ">
                                                <i class="fa {{ $user->id == Auth::user()->id ? 'fa-check' : 'fa-times' }}"
                                                    style="font-size: 9px; color: #ffffff"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </li>
                <li class="nav-item d-flex align-items-center ps-2">
                    <div class="nav-item d-flex align-self-end">
                        <a href="javascript:;" class="btn btn-primary active mb-0 text-white"
                            onclick="confirmSignOut()">
                            <i class="fa fa-sign-out-alt me-sm-1"></i>
                            <span class="d-sm-inline d-none">Keluar</span>
                        </a>
                    </div>
                </li>
                <li class="nav-item d-flex align-items-center ps-2">
                    <div class="nav-item d-flex align-self-end">
                        <a href="{{ route('information') }}" class="btn btn-info active mb-0 text-white">
                            <i class="fa fa-info-circle fa-lg"></i>
                        </a>
                    </div>
                </li>
                {{-- <li class="nav-item dropdown pe-2 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-bell cursor-pointer"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                        <li class="mb-2">
                            <a class="dropdown-item border-radius-md" href="javascript:;">
                                <div class="d-flex py-1">
                                    <div class="my-auto">
                                        <img src="../assets/img/team-2.jpg" class="avatar avatar-sm  me-3 ">
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm font-weight-normal mb-1">
                                            <span class="font-weight-bold">New message</span> from Laur
                                        </h6>
                                        <p class="text-xs text-secondary mb-0">
                                            <i class="fa fa-clock me-1"></i>
                                            13 minutes ago
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="mb-2">
                            <a class="dropdown-item border-radius-md" href="javascript:;">
                                <div class="d-flex py-1">
                                    <div class="my-auto">
                                        <img src="../assets/img/small-logos/logo-spotify.svg"
                                            class="avatar avatar-sm bg-gradient-dark  me-3 ">
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm font-weight-normal mb-1">
                                            <span class="font-weight-bold">New album</span> by Travis Scott
                                        </h6>
                                        <p class="text-xs text-secondary mb-0">
                                            <i class="fa fa-clock me-1"></i>
                                            1 day
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item border-radius-md" href="javascript:;">
                                <div class="d-flex py-1">
                                    <div class="avatar avatar-sm bg-gradient-secondary  me-3  my-auto">
                                        <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1"
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <title>credit-card</title>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF"
                                                    fill-rule="nonzero">
                                                    <g transform="translate(1716.000000, 291.000000)">
                                                        <g transform="translate(453.000000, 454.000000)">
                                                            <path class="color-background"
                                                                d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z"
                                                                opacity="0.593633743"></path>
                                                            <path class="color-background"
                                                                d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z">
                                                            </path>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm font-weight-normal mb-1">
                                            Payment successfully completed
                                        </h6>
                                        <p class="text-xs text-secondary mb-0">
                                            <i class="fa fa-clock me-1"></i>
                                            2 days
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li> --}}
            </ul>
        </div>
    </div>
</nav>
<!-- End Navbar -->

<script>
    function confirmSignOut() {
        Swal.fire({
            title: 'Yakin ingin keluar?',
            html: `Anda akan keluar dari sistem, login kembali untuk masuk`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Keluar!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('logout') }}";
            }
        });
    }

    function confirmSwitch(userId, photo, name, email, role) {
        const borderColor = role === 'master' ? 'border-primary' : 'border-warning';

        Swal.fire({
            title: 'Yakin untuk beralih akun?',
            html: `
                <hr style="margin: 10px 0;">
                <div style="
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                ">
                    <div style="
                        border: 1px solid #ddd;
                        border-radius: 10px;
                        padding: 15px 20px;
                        width: 100%;
                        max-width: 350px;
                        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
                        text-align: center;
                        margin-bottom: 15px;
                    ">
                        <img src="${photo ? photo : '/assets/img/default-profile.jpg'}" 
                            alt="Profile"
                            class="rounded-circle border ${borderColor} border-3"
                            style="width: 60px; height: 60px; object-fit: cover; margin-bottom: 10px;">
                        <div style="font-weight: bold; font-size: 16px;">${name}</div>
                        <div style="color: #6c757d; font-size: 13px;">${email}</div>
                    </div>

                    <input type="password" id="switchPassword" class="swal2-input" placeholder="Masukkan password" style="max-width: 300px;">
                </div>
            `,
            preConfirm: () => {
                const password = document.getElementById('switchPassword').value;
                if (!password) {
                    Swal.showValidationMessage('Password is required');
                }
                return { password: password };
            },
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, beralih!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const password = result.value.password;

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('switch-account') }}";

                const token = document.createElement('input');
                token.type = 'hidden';
                token.name = '_token';
                token.value = '{{ csrf_token() }}';
                form.appendChild(token);

                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'user_id';
                idInput.value = userId;
                form.appendChild(idInput);

                const pwInput = document.createElement('input');
                pwInput.type = 'hidden';
                pwInput.name = 'password';
                pwInput.value = password;
                form.appendChild(pwInput);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function updateUnreadBadge() {
        $.get('{{ route("chat.unread-count") }}', function (data) {
            const count = data.unread;
            const badge = $('#chat-unread-badge');

            if (count > 0) {
                badge.removeClass('d-none').text(count);
            } else {
                badge.addClass('d-none').text(0);
            }
        });
    }

    // Jalankan saat page load
    updateUnreadBadge();

    // Jalankan ulang tiap 1 detik
    setInterval(updateUnreadBadge, 5000);
</script>