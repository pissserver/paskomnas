@extends('layouts.user_type.auth')

@section('title', 'Barang Komoditi Paskomnas || PISS')

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
            {{ session('success') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <i class="fa fa-close" aria-hidden="true"></i>
        </button>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div class="d-flex align-items-center bg-gradient-secondary ps-3 pe-3 rounded-2 text-white">
                            <i class="fa fa-box me-2"></i>
                            <h6 class="mb-0 text-white">Data Barang Komoditi</h6>
                        </div>
                        <div class="d-flex align-items-center">
                            <input type="text" class="form-control form-control-sm me-1" id="searchGoods"
                                placeholder="Cari barang komoditi..." name="search" style="max-width: 200px;">
                            <span class="input-group-text" onclick="togglePassword('password_confirmation', this)">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </span>
                        </div>
                        <div class="d-flex align-items-center">
                            <a href="{{ route('goods.create') }}" class="btn bg-gradient-primary btn-sm mb-0"
                                type="button">Tambah Barang Komoditi &nbsp;&nbsp; <i class="fa fa-plus"
                                    style="padding-bottom: 7px;"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-4 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0 table-hover table-striped">
                            <thead>
                                <tr>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        No
                                    </th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Nama Barang
                                    </th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Satuan
                                    </th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="goodsTableBody">

                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="d-flex justify-content-center mt-3 mb-2" id="pagination">

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(goodsId, goodsName) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            html: `Customer <strong>${goodsName}</strong> akan dihapus permanen.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${goodsId}`).submit();
            }
        });
    }

    function loadGoods(keyword = '', page = 1) {
        fetch(`{{ route('goods.data') }}?keyword=${encodeURIComponent(keyword)}&page=${page}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('goodsTableBody').innerHTML = data.html;
                document.getElementById('pagination').innerHTML = data.pagination;

                // Re-bind pagination click
                document.querySelectorAll('#pagination a').forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const url = new URL(this.href);
                        const page = url.searchParams.get('page');
                        const keyword = document.getElementById('searchGoods').value;
                        loadGoods(keyword, page);
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

    document.addEventListener('DOMContentLoaded', () => loadGoods());

    document.getElementById('searchGoods').addEventListener('keyup', function () {
        loadGoods(this.value);
    });
</script>

@endsection