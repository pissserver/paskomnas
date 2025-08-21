@extends('layouts.user_type.auth')

@section('title', 'Batalkan Order Paskomnas || PISS')

@section('content')

<div>
    @if($errors->any())
    <div class="mx-4 alert alert-primary alert-dismissible fade show" role="alert">
        <span class="alert-text text-white">
            {{$errors->first()}}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <i class="fa fa-close" aria-hidden="true"></i>
        </button>
    </div>
    @endif
    @if(session('success'))
    <div class="mx-4 alert alert-success alert-dismissible fade show" id="alert-success" role="alert">
        <span class="alert-text text-white">
            {!! session('success') !!}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <i class="fa fa-close" aria-hidden="true"></i>
        </button>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card py-3">
                <div class="nav-wrapper position-relative d-flex justify-content-center pt-1">
                    <ul class="nav nav-pills bg-transparent" role="tablist">
                        <!-- Tab: Order Diproses -->
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active mb-2 me-2" id="proccess-tab" data-bs-toggle="tab" href="#proccess"
                                role="tab" aria-controls="proccess" aria-selected="true">
                                <i class="fas fa-sync-alt me-1 text-primary"></i> Diproses
                            </a>
                        </li>

                        <!-- Tab: Order Dibatalkan -->
                        <li class="nav-item" role="presentation">
                            <a class="nav-link mb-2" id="cancelled-tab" data-bs-toggle="tab" href="#cancelled"
                                role="tab" aria-controls="cancelled" aria-selected="false">
                                <i class="fas fa-ban me-1 text-primary"></i> Dibatalkan
                            </a>
                        </li>

                        <!-- Tab: Order Selesai -->
                        <li class="nav-item" role="presentation">
                            <a class="nav-link mb-2" id="done-tab" data-bs-toggle="tab" href="#done" role="tab"
                                aria-controls="done" aria-selected="false">
                                <i class="fas fa-check me-1 text-primary"></i> Selesai
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-content">
        {{-- Tab Order Diproses --}}
        <div class="tab-pane fade show active" id="proccess" role="tabpanel" aria-labelledby="proccess-tab">
            <div class="row order-process mt-5">
                <div class="col-12">
                    <div class="card mb-4 mx-4">
                        <div class="card-header pb-0">
                            <div class="d-flex flex-row justify-content-between">
                                <div
                                    class="d-flex align-items-center bg-gradient-primary ps-3 pe-3 rounded-2 text-white">
                                    <i class="fa fa-shopping-cart me-2"></i>
                                    <h6 class="mb-0 text-white">Data Order Diproses</h6>
                                </div>
                                <div class="d-flex align-items-center">
                                    <input type="text" class="form-control form-control-sm me-1" id="searchOrderProcess"
                                        placeholder="Cari order..." name="search" style="max-width: 200px;">
                                    <span class="input-group-text"
                                        onclick="togglePassword('password_confirmation', this)">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-4 pb-2">
                            <div class="table-responsive p-0">
                                @if ($orderProcess->isEmpty())
                                <div class="empty-data">
                                    <div class="text-center">
                                        <img src="{{ asset('assets/img/empty-data.png') }}" alt="No Data"
                                            class="img-fluid" width="450">
                                        <h4 class="text-secondary">Belum ada order diproses</h4>
                                    </div>
                                </div>
                                @else
                                <table class="table align-items-center mb-0 table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                No
                                            </th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Nama Customer
                                            </th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Kode Order
                                            </th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Tanggal Order
                                            </th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Status
                                            </th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Aksi
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="orderProcessTableBody">

                                    </tbody>
                                </table>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex justify-content-center mt-3 mb-2" id="pagination">

                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab Order Dibatalkan --}}
        <div class="tab-pane fade" id="cancelled" role="tabpanel" aria-labelledby="proccess-tab">
            <div class="row order-cancelled mt-5">
                <div class="col-12">
                    <div class="card mb-4 mx-4">
                        <div class="card-header pb-0">
                            <div class="d-flex flex-row justify-content-between">
                                <div
                                    class="d-flex align-items-center bg-gradient-danger ps-3 pe-3 rounded-2 text-white">
                                    <i class="fa fa-shopping-cart me-2"></i>
                                    <h6 class="mb-0 text-white">Data Order Dibatalkan</h6>
                                </div>
                                <div class="d-flex align-items-center">
                                    <input type="text" class="form-control form-control-sm me-1" id="searchOrderCancel"
                                        placeholder="Cari order..." name="search" style="max-width: 200px;">
                                    <span class="input-group-text"
                                        onclick="togglePassword('password_confirmation', this)">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-4 pb-2">
                            <div class="table-responsive p-0">
                                @if ($orderCancel->total() === 0)
                                <div class="empty-data">
                                    <div class="text-center">
                                        <img src="{{ asset('assets/img/empty-data.png') }}" alt="No Data"
                                            class="img-fluid" width="450">
                                        <h4 class="text-secondary">Belum ada order dibatalkan</h4>
                                    </div>
                                </div>
                                @else
                                <table class="table align-items-center mb-0 table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                No
                                            </th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Nama Customer
                                            </th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Kode Order
                                            </th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Tanggal Order
                                            </th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Status
                                            </th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Aksi
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="orderCancelTableBody">

                                    </tbody>
                                </table>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex justify-content-center mt-3 mb-2" id="pagination">

                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab Order Selesai --}}
        <div class="tab-pane fade" id="done" role="tabpanel" aria-labelledby="done-tab">
            <div class="row order-done mt-5">
                <div class="col-12">
                    <div class="card mb-4 mx-4">
                        <div class="card-header pb-0">
                            <div class="d-flex flex-row justify-content-between">
                                <div
                                    class="d-flex align-items-center bg-gradient-success ps-3 pe-3 rounded-2 text-white">
                                    <i class="fa fa-shopping-cart me-2"></i>
                                    <h6 class="mb-0 text-white">Data Order Selesai</h6>
                                </div>
                                <div class="d-flex align-items-center">
                                    <input type="text" class="form-control form-control-sm me-1" id="searchOrderDone"
                                        placeholder="Cari order..." name="search" style="max-width: 200px;">
                                    <span class="input-group-text"
                                        onclick="togglePassword('password_confirmation', this)">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-4 pb-2">
                            <div class="table-responsive p-0">
                                @if ($orderDone->total() === 0)
                                <div class="empty-data">
                                    <div class="text-center">
                                        <img src="{{ asset('assets/img/empty-data.png') }}" alt="No Data"
                                            class="img-fluid" width="450">
                                        <h4 class="text-secondary">Belum ada order selesai</h4>
                                    </div>
                                </div>
                                @else
                                <table class="table align-items-center mb-0 table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                No
                                            </th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Nama Customer
                                            </th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Kode Order
                                            </th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Tanggal Order
                                            </th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Status
                                            </th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Aksi
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="orderDoneTableBody">

                                    </tbody>
                                </table>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex justify-content-center mt-3 mb-2" id="pagination">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>

<script>
    function confirmCancel(orderId, orderCode, customerName) {
        Swal.fire({
            title: 'Yakin ingin membatalkan?',
            html: `Order dengan kode <strong>${orderCode}</strong> dari customer <strong>${customerName}</strong>.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Batalkan!',
            cancelButtonText: 'Kembali'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`cancel-form-${orderId}`).submit();
            }
        });
    }

    function confirmProcess(orderId, orderCode, customerName) {
        Swal.fire({
            title: 'Yakin ingin proses kembali?',
            html: `Order dengan kode <strong>${orderCode}</strong> dari customer <strong>${customerName}</strong>.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Proses kembali!',
            cancelButtonText: 'Kembali'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`process-form-${orderId}`).submit();
            }
        });
    }

    function confirmDone(orderId, orderCode, customerName) {
        Swal.fire({
            title: 'Yakin ingin selesaikan?',
            html: `Order dengan kode <strong>${orderCode}</strong> dari customer <strong>${customerName}</strong>.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Selesaikan!',
            cancelButtonText: 'Kembali'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`done-form-${orderId}`).submit();
            }
        });
    }

    function loadOrderProcess(keyword = '', page = 1) {
        fetch(`{{ route('order.process.data') }}?keyword=${encodeURIComponent(keyword)}&page=${page}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('orderProcessTableBody').innerHTML = data.html;
                document.getElementById('pagination').innerHTML = data.pagination;

                // Re-bind pagination click
                document.querySelectorAll('#pagination a').forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const url = new URL(this.href);
                        const page = url.searchParams.get('page');
                        const keyword = document.getElementById('searchOrderProcess').value;
                        loadOrderProcess(keyword, page);
                    });
                });

                if (window.activeZoom) {
                    window.activeZoom.detach();
                }
                window.activeZoom = mediumZoom('.zoomable', {
                    margin: 24,
                    background: '#000'
                });
            });
    }

    function loadOrderCancel(keyword = '', page = 1) {
        fetch(`{{ route('order.cancel.data') }}?keyword=${encodeURIComponent(keyword)}&page=${page}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('orderCancelTableBody').innerHTML = data.html;
                document.getElementById('pagination').innerHTML = data.pagination;

                // Re-bind pagination click
                document.querySelectorAll('#pagination a').forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const url = new URL(this.href);
                        const page = url.searchParams.get('page');
                        const keyword = document.getElementById('searchOrderCancel').value;
                        loadOrderCancel(keyword, page);
                    });
                });

                if (window.activeZoom) {
                    window.activeZoom.detach();
                }
                window.activeZoom = mediumZoom('.zoomable', {
                    margin: 24,
                    background: '#000'
                });
            });
    }

    function loadOrderDone(keyword = '', page = 1) {
        fetch(`{{ route('order.done.data') }}?keyword=${encodeURIComponent(keyword)}&page=${page}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('orderDoneTableBody').innerHTML = data.html;
                document.getElementById('pagination').innerHTML = data.pagination;

                // Re-bind pagination click
                document.querySelectorAll('#pagination a').forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const url = new URL(this.href);
                        const page = url.searchParams.get('page');
                        const keyword = document.getElementById('searchOrderDone').value;
                        loadOrderDone(keyword, page);
                    });
                });

                if (window.activeZoom) {
                    window.activeZoom.detach();
                }
                window.activeZoom = mediumZoom('.zoomable', {
                    margin: 24,
                    background: '#000'
                });
            });
    }

    document.addEventListener('DOMContentLoaded', () => loadOrderProcess());

    document.addEventListener('DOMContentLoaded', () => loadOrderCancel());

    document.addEventListener('DOMContentLoaded', () => loadOrderDone());

    document.getElementById('searchOrderProcess').addEventListener('keyup', function () {
        loadOrderProcess(this.value);
    });

    document.getElementById('searchOrderCancel').addEventListener('keyup', function () {
        loadOrderCancel(this.value);
    });

    document.getElementById('searchOrderDone').addEventListener('keyup', function () {
        loadOrderDone(this.value);
    });
</script>

@endsection