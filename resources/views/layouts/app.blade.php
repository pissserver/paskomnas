<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/logo2.png') }}">
	<link rel="icon" type="image/png" href="{{ asset('assets/img/logo2.png') }}">
	<title>@yield('title')</title>

	{{-- Fonts awesome --}}
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
	<link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
	<link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
	<script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

	{{-- MediumZoom --}}
	<script src="https://cdn.jsdelivr.net/npm/medium-zoom@1.0.6/dist/medium-zoom.min.js"></script>

	{{-- Select2 --}}
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
		rel="stylesheet" />

	{{-- CSS --}}
	<link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
	<link id="pagestyle" href="{{ asset('assets/css/soft-ui-dashboard.css?v=1.0.3') }}" rel="stylesheet" />

	{{-- Cropper.js --}}
	<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">

	{{-- Flatpickr --}}
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

	{{-- jQuery --}}
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
	<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
</head>

<body class="g-sidenav-show bg-gray-100 {{ (\Request::is('virtual-reality') ? 'virtual-reality' : '') }} ">

	@auth
	@yield('auth')
	@endauth

	@guest
	@yield('guest')
	@endguest

	{{-- @if(session()->has('success'))
	<div x-data="{ show: true}" x-init="setTimeout(() => show = false, 4000)" x-show="show"
		class="position-fixed bg-success rounded right-3 text-sm py-2 px-4">
		<p class="m-0">{{ session('success')}}</p>
	</div>
	@endif --}}

	{{-- Container Notifikasi Chat --}}
	<div id="chat-notification-container" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999;"></div>

	<!-- Core JS Files -->
	<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
	<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
	<script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
	<script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
	<script src="{{ asset('assets/js/plugins/fullcalendar.min.js') }}"></script>
	<script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>

	@stack('dashboard')

	<script>
		var win = navigator.platform.indexOf('Win') > -1;
		if (win && document.querySelector('#sidenav-scrollbar')) {
			var options = { damping: '0.5' }
			Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
		}
	</script>

	{{-- Script Notifikasi Chat, hanya untuk user login --}}
	@auth
	<script>
		let lastNotifiedIds = [];

		function showChatNotification(data) {
			const notifId = 'notif-' + data.id;
			if (document.getElementById(notifId)) return;

			const notif = document.createElement('div');
			notif.id = notifId;
			notif.className = 'card text-white mb-2';
			notif.style.width = '400px';
			notif.style.backgroundColor = '#1d1d1d';
			notif.style.boxShadow = '0 0 10px rgba(0,0,0,0.5)';
			notif.innerHTML = `
				<div class="card-body d-flex align-items-center cursor-pointer">
					<img src="${data.avatar}" class="rounded-circle me-2" width="40" height="40" style="object-fit:cover;">
					<div style="flex-grow:1;">
						<strong>${data.name}</strong><br>
						<small class="message-content">${data.message}</small>
					</div>
					<span class="ms-2 text-white" style="font-size: 0.75rem;">${data.time}</span>
				</div>
			`;

			notif.addEventListener('click', () => {
				window.location.href = data.url;
			});

			document.getElementById('chat-notification-container').appendChild(notif);

			setTimeout(() => {
				notif.remove();
			}, 5000);
		}

		setInterval(() => {
			fetch("{{ route('chat.unread-messages') }}", {
				headers: {
					'Accept': 'application/json'
				}
			})
			.then(res => {
				const contentType = res.headers.get("content-type") || "";
				if (contentType.includes("application/json")) {
					return res.json();
				} else {
					console.warn("Respon bukan JSON, kemungkinan HTML/login page:", res);
					return null; // skip supaya tidak error
				}
			})
			.then(data => {
				if (!data || !data.messages) return;

				data.messages.forEach(msg => {
					if (!lastNotifiedIds.includes(msg.id)) {
						showChatNotification(msg);
						lastNotifiedIds.push(msg.id);

						if (lastNotifiedIds.length > 20) lastNotifiedIds.shift();
					}
				});
			})
			.catch(err => {
				console.error("Gagal ambil notif:", err);
			});
		}, 1000); // cek tiap 1 detik
	</script>
	@endauth

	<!-- Bootstrap 5 Bundle -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
	</script>
	<script async defer src="https://buttons.github.io/buttons.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="{{ asset('assets/js/soft-ui-dashboard.min.js?v=1.0.3') }}"></script>
</body>

</html>