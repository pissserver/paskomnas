<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3"
	id="sidenav-main">
	<div class="sidenav-header">
		<i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
			aria-hidden="true" id="iconSidenav"></i>
		<a class="align-items-center d-flex m-0 navbar-brand text-wrap" href="{{ route('dashboard') }}">
			<img src="{{ asset('assets/img/logo.png') }}" class="navbar-brand-img" style="width: 100%;" alt="...">
			{{-- <span class="ms-3 font-weight-bold">PASKOMNAS</span> --}}
		</a>
	</div>

	<hr class="horizontal dark mt-0">
	<div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main" style="height: 100vh;">
		<ul class="navbar-nav">
			<li class="nav-item">
				<a class="nav-link {{ (Request::is('dashboard') ? 'active' : '') }}" href="{{ url('dashboard') }}">
					<div
						class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
						<i style="margin-top: -5px;"
							class="fas fa-tachometer-alt {{ (Request::is('dashboard') ? 'text-white' : 'text-dark') }}"></i>
					</div>
					<span class="nav-link-text ms-1">Dashboard</span>
				</a>
			</li>

			<li class="nav-item mt-3">
				<h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Order</h6>
			</li>

			{{-- <li class="nav-item">
				<a class="nav-link {{ (Request::is('billing') ? 'active' : '') }}" href="{{ url('billing') }}">
			<div
				class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
				<svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1" xmlns="http://www.w3.org/2000/svg"
					xmlns:xlink="http://www.w3.org/1999/xlink">
					<title>credit-card</title>
					<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						<g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF" fill-rule="nonzero">
							<g transform="translate(1716.000000, 291.000000)">
								<g transform="translate(453.000000, 454.000000)">
									<path class="color-background opacity-6"
										d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z">
									</path>
									<path class="color-background"
										d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z">
									</path>
								</g>
							</g>
						</g>
					</g>
				</svg>
			</div>
			<span class="nav-link-text ms-1">Billing</span>
			</a>
			</li> --}}

			<li class="nav-item">
				<a class="nav-link {{ Request::is('order') || Request::is('buat-order/*') || Request::is('arsip-gambar/*/edit')  ? 'active' : '' }}"
					href="{{ route('order.index') }}">
					<div
						class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
						<i style="margin-top: -5px;"
							class="fas fa-shopping-cart {{ (Request::is('order') || Request::is('buat-order/*') || Request::is('arsip-gambar/*/edit') ? 'text-white' : 'text-dark') }}"></i>
					</div>
					<span class="nav-link-text ms-1">Order</span>
				</a>
			</li>

			{{-- <li class="nav-item">
				<a class="nav-link {{ Request::is('arsip-gambar') || Request::is('arsip-gambar/create') || Request::is('arsip-gambar/*/edit')  ? 'active' : '' }}"
			href="{{ route('image-archive.index') }}">
			<div
				class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
				<i style="margin-top: -5px;"
					class="fas fa-images {{ (Request::is('arsip-gambar') || Request::is('arsip-gambar/create') || Request::is('arsip-gambar/*/edit') ? 'text-white' : 'text-dark') }}"></i>
			</div>
			<span class="nav-link-text ms-1">Arsip Gambar</span>
			</a>
			</li> --}}

			<li class="nav-item mt-3">
				<h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Proses</h6>
			</li>

			<li class="nav-item">
				<a class="nav-link {{ Request::is('order-diproses')  ? 'active' : '' }}"
					href="{{ route('order.process.view') }}">
					<div
						class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
						<i style="margin-top: -5px;"
							class="fa fa-sync-alt {{ (Request::is('order-diproses') ? 'text-white' : 'text-dark') }}"></i>
					</div>
					<span class="nav-link-text ms-1">Proses & Batal</span>
				</a>
			</li>

			<li class="nav-item">
				<a class="nav-link {{ Request::is('purchase')  ? 'active' : '' }}" href="{{ route('purchase.index') }}">
					<div
						class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
						<i style="margin-top: -5px;"
							class="fa fa-shopping-bag {{ (Request::is('purchase') ? 'text-white' : 'text-dark') }}"></i>
					</div>
					<span class="nav-link-text ms-1">Purchase</span>
				</a>
			</li>

			<li class="nav-item">
				<a class="nav-link {{ Request::is('surat-jalan')  ? 'active' : '' }}"
					href="{{ route('document.index') }}">
					<div
						class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
						<i style="margin-top: -5px;"
							class="fa fa-clipboard-list {{ (Request::is('surat-jalan') ? 'text-white' : 'text-dark') }}"></i>
					</div>
					<span class="nav-link-text ms-1">Surat Jalan</span>
				</a>
			</li>

			<li class="nav-item">
				<a class="nav-link {{ Request::is('invoice')  ? 'active' : '' }}" href="{{ route('invoice.index') }}">
					<div
						class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
						<i style="margin-top: -5px;"
							class="fa fa-file-invoice {{ (Request::is('invoice') ? 'text-white' : 'text-dark') }}"></i>
					</div>
					<span class="nav-link-text ms-1">Invoice</span>
				</a>
			</li>

			<li class="nav-item mt-3">
				<h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Master</h6>
			</li>

			<li class="nav-item">
				<a class="nav-link {{ Request::is('customer-group') || Request::is('customer-group/tambah') || Request::is('customer-group/*/edit')  ? 'active' : '' }}"
					href="{{ route('customer.group.index') }}">
					<div
						class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
						<i style="margin-top: -5px;"
							class="fa fa-users {{ (Request::is('customer-group') || Request::is('customer-group/tambah') || Request::is('customer-group/*/edit') ? 'text-white' : 'text-dark') }}"></i>
					</div>
					<span class="nav-link-text ms-1">Customer Group

					</span>
				</a>
			</li>

			<li class="nav-item">
				<a class="nav-link {{ Request::is('customer') || Request::is('customer/tambah') || Request::is('customer/*/detail')  ? 'active' : '' }}"
					href="{{ route('customer.index') }}">
					<div
						class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
						<i style="margin-top: -5px;"
							class="fa fa-briefcase {{ (Request::is('customer') || Request::is('customer/tambah') || Request::is('customer/*/detail') ? 'text-white' : 'text-dark') }}"></i>
					</div>
					<span class="nav-link-text ms-1">Customer</span>
				</a>
			</li>

			<li class="nav-item">
				<a class="nav-link {{ Request::is('barang-komoditi') || Request::is('barang-komoditi/tambah') || Request::is('barang-komoditi/*/edit')  ? 'active' : '' }}"
					href="{{ route('goods.index') }}">
					<div
						class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
						<i style="margin-top: -5px;"
							class="fa fa-box {{ (Request::is('barang-komoditi') || Request::is('barang-komoditi/tambah') || Request::is('barang-komoditi/*/edit') ? 'text-white' : 'text-dark') }}"></i>
					</div>
					<span class="nav-link-text ms-1">Barang Komoditi</span>
				</a>
			</li>

			<li class="nav-item">
				<a class="nav-link {{ Request::is('spesifikasi-komoditi') || Request::is('spesifikasi-komoditi/tambah') || Request::is('spesifikasi-komoditi/*/edit')  ? 'active' : '' }}"
					href="{{ route('specification.index') }}">
					<div
						class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
						<i style="margin-top: -5px;"
							class="fa fa-puzzle-piece {{ (Request::is('spesifikasi-komoditi') || Request::is('spesifikasi-komoditi/tambah') || Request::is('spesifikasi-komoditi/*/edit') ? 'text-white' : 'text-dark') }}"></i>
					</div>
					<span class="nav-link-text ms-1">Spesifikasi Komoditi</span>
				</a>
			</li>

			<li class="nav-item">
				<a class="nav-link {{ Request::is('kelola-pengguna') || Request::is('kelola-pengguna/tambah') || Request::is('kelola-pengguna/*/edit')  ? 'active' : '' }}"
					href="{{ route('user-management.index') }}">
					<div
						class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
						<i style="margin-top: -5px;"
							class="fas fa-users {{ (Request::is('kelola-pengguna') || Request::is('kelola-pengguna/tambah') || Request::is('kelola-pengguna/*/edit') ? 'text-white' : 'text-dark') }}"></i>
					</div>
					<span class="nav-link-text ms-1">Kelola Pengguna</span>
				</a>
			</li>

			<li class="nav-item mt-3">
				<h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Akun</h6>
			</li>

			<li class="nav-item">
				<a class="nav-link {{ (Request::is('profil') ? 'active' : '') }} " href="{{ route('user.index') }}">
					<div
						class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
						<i style="margin-top: -5px;"
							class="fas fa-user {{ (Request::is('profil') ? 'text-white' : 'text-dark') }}"></i>
					</div>
					<span class="nav-link-text ms-1">Profil</span>
				</a>
			</li>

			<li class="nav-item">
				<a class="nav-link {{ (Request::is('virtual-reality') ? 'active' : '') }}"
					href="{{ url('virtual-reality') }}">
					<div
						class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
						<svg width="12px" height="12px" viewBox="0 0 42 42" version="1.1"
							xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
							<title>box-3d-50</title>
							<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
								<g transform="translate(-2319.000000, -291.000000)" fill="#FFFFFF" fill-rule="nonzero">
									<g transform="translate(1716.000000, 291.000000)">
										<g transform="translate(603.000000, 0.000000)">
											<path class="color-background"
												d="M22.7597136,19.3090182 L38.8987031,11.2395234 C39.3926816,10.9925342 39.592906,10.3918611 39.3459167,9.89788265 C39.249157,9.70436312 39.0922432,9.5474453 38.8987261,9.45068056 L20.2741875,0.1378125 L20.2741875,0.1378125 C19.905375,-0.04725 19.469625,-0.04725 19.0995,0.1378125 L3.1011696,8.13815822 C2.60720568,8.38517662 2.40701679,8.98586148 2.6540352,9.4798254 C2.75080129,9.67332903 2.90771305,9.83023153 3.10122239,9.9269862 L21.8652864,19.3090182 C22.1468139,19.4497819 22.4781861,19.4497819 22.7597136,19.3090182 Z">
											</path>
											<path class="color-background opacity-6"
												d="M23.625,22.429159 L23.625,39.8805372 C23.625,40.4328219 24.0727153,40.8805372 24.625,40.8805372 C24.7802551,40.8805372 24.9333778,40.8443874 25.0722402,40.7749511 L41.2741875,32.673375 L41.2741875,32.673375 C41.719125,32.4515625 42,31.9974375 42,31.5 L42,14.241659 C42,13.6893742 41.5522847,13.241659 41,13.241659 C40.8447549,13.241659 40.6916418,13.2778041 40.5527864,13.3472318 L24.1777864,21.5347318 C23.8390024,21.7041238 23.625,22.0503869 23.625,22.429159 Z">
											</path>
											<path class="color-background opacity-6"
												d="M20.4472136,21.5347318 L1.4472136,12.0347318 C0.953235098,11.7877425 0.352562058,11.9879669 0.105572809,12.4819454 C0.0361450918,12.6208008 6.47121774e-16,12.7739139 0,12.929159 L0,30.1875 L0,30.1875 C0,30.6849375 0.280875,31.1390625 0.7258125,31.3621875 L19.5528096,40.7750766 C20.0467945,41.0220531 20.6474623,40.8218132 20.8944388,40.3278283 C20.963859,40.1889789 21,40.0358742 21,39.8806379 L21,22.429159 C21,22.0503869 20.7859976,21.7041238 20.4472136,21.5347318 Z">
											</path>
										</g>
									</g>
								</g>
							</g>
						</svg>
					</div>
					<span class="nav-link-text ms-1">Virtual Reality</span>
				</a>
			</li>
		</ul>
	</div>
</aside>