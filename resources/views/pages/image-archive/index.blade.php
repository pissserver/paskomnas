@extends('layouts.user_type.auth')

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
            <form action="{{ route('image-archive.store') }}" method="POST" role="form text-left"
                enctype="multipart/form-data">
                @csrf
                <div class="container-fluid py-4">
                    <div class="card">
                        <div class="card-header p-4">
                            <h6 class="mb-0">{{ __('Arsip Gambar') }}</h6>
                        </div>
                        <div class="card-body pt-1 p-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-control-label">{{ __('Nama') }}</label>
                                        <div class="@error('archive.name')border border-danger rounded-3 @enderror">
                                            <input class="form-control" type="text" placeholder="Nama Gambar" id="name"
                                                name="name">
                                            @error('name')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="imageInput" class="form-label mb-2">Gambar</label>
                                        <div class="row">
                                            <div class="col-md-1">
                                                <div id="imagePreview" class="zoomable"
                                                    style="border: 2px dashed #DE0A93; border-radius: 8px; width: 43px; height: 43px; background-color: #ffffff; background-position: center; background-size: cover; background-repeat: no-repeat;">
                                                </div>
                                            </div>
                                            <div class="col-md-11">
                                                <input type="file" id="imageInput" name="photo" accept="image/*"
                                                    class="d-none" onchange="previewSelectedImage(event)">
                                                <a href="javascript:;"
                                                    onclick="document.getElementById('imageInput').click()"
                                                    class="btn bg-gradient-primary w-100">
                                                    <i class="fa fa-upload pe-2" data-bs-toggle="tooltip"
                                                        title="Upload Image"></i>Unggah Gambar
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="form-group">
                                <label for="about">{{ 'Deskripsi' }}</label>
                                <div class="@error('archive.about') border border-danger rounded-3 @enderror">
                                    <textarea class="form-control" id="about" rows="3" placeholder="Deskripsi Gambar"
                                        name="description"></textarea>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit"
                                    class="btn bg-gradient-dark btn-md mt-2 mb-2">{{ 'Simpan' }}&nbsp;&nbsp; <i
                                        class="fa fa-save"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="bg-gradient-dark px-3 py-2 rounded-2 text-white d-flex align-items-center">
                                <i class="fa fa-images me-2"></i>
                                <h6 class="mb-0 text-white">Data Arsip Gambar</h6>
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            <input type="text" class="form-control form-control-sm me-1" id="searchData"
                                placeholder="Cari data..." name="search" style="max-width: 200px;">
                            <span class="input-group-text" style="cursor: pointer;">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </span>
                        </div>

                        <div class="d-flex">
                            <div class="dropdown">
                                <button class="btn bg-gradient-danger" type="button" id="dropdownHapusSemua"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-trash-alt me-1"></i> Hapus Semua
                                </button>
                                <div class="dropdown-menu dropdown-menu-end p-3 shadow" style="min-width: 300px;"
                                    aria-labelledby="dropdownHapusSemua">
                                    <form action="" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus semua data dalam rentang waktu ini?')">
                                        @csrf
                                        <div class="mb-2">
                                            <label for="start_date" class="form-label mb-1 text-xs">Dari Tanggal</label>
                                            <input type="date" name="start_date" id="start_date"
                                                class="form-control form-control-sm" required>
                                        </div>
                                        <div class="mb-2">
                                            <label for="end_date" class="form-label mb-1 text-xs">Sampai Tanggal</label>
                                            <input type="date" name="end_date" id="end_date"
                                                class="form-control form-control-sm" required>
                                        </div>
                                        <button type="submit" class="btn btn-sm bg-gradient-danger w-100">
                                            <i class="fas fa-trash-alt me-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-items-center mb-0">
                            <thead class="bg-gradient-dark">
                                <tr>
                                    <th class="text-uppercase text-white text-xxs font-weight-bolder opacity-7"
                                        width="5%">
                                        No
                                    </th>
                                    <th class="text-center text-uppercase text-white text-xxs font-weight-bolder opacity-7"
                                        width="10%">
                                        Gambar
                                    </th>
                                    <th class="text-center text-uppercase text-white text-xxs font-weight-bolder opacity-7"
                                        width="20%">
                                        Nama Gambar
                                    </th>
                                    <th class="text-center text-uppercase text-white text-xxs font-weight-bolder opacity-7"
                                        width="35%">
                                        Deskripsi
                                    </th>
                                    <th class="text-center text-uppercase text-white text-xxs font-weight-bolder opacity-7"
                                        width="15%">
                                        Tanggal Dibuat
                                    </th>
                                    <th class="text-center text-uppercase text-white text-xxs font-weight-bolder opacity-7"
                                        width="15%">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="archiveTableBody">

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
    function previewSelectedImage(event) {
        const input = event.target;
        const preview = document.getElementById('imagePreview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.style.backgroundImage = `url('${e.target.result}')`;
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.backgroundImage = '';
        }
    }
    
    function confirmDelete(archiveId, archiveName) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            html: `Data <strong>${archiveName}</strong> akan dihapus permanent.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${archiveId}`).submit();
            }
        });
    }

    function loadData(keyword = '', page = 1) {
        fetch(`{{ route('image-archive.data') }}?keyword=${encodeURIComponent(keyword)}&page=${page}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('archiveTableBody').innerHTML = data.html;
            document.getElementById('pagination').innerHTML = data.pagination;

            // Re-bind pagination click
            document.querySelectorAll('#pagination a').forEach(link => {
            link.addEventListener('click', function(e) {
            e.preventDefault();
            const url = new URL(this.href);
            const page = url.searchParams.get('page');
            const keyword = document.getElementById('searchData').value;
            loadData(keyword, page);
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

    document.addEventListener('DOMContentLoaded', () => loadData());

    document.getElementById('searchData').addEventListener('keyup', function () {
        loadData(this.value);
    });
</script>

@endsection