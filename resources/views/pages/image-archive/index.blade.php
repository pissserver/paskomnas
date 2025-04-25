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
                            <h6 class="mb-0">{{ __('Create Image Archive') }}</h6>
                        </div>
                        <div class="card-body pt-1 p-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-control-label">{{ __('Name') }}</label>
                                        <div class="@error('archive.name')border border-danger rounded-3 @enderror">
                                            <input class="form-control" type="text" placeholder="Name" id="name"
                                                name="name">
                                            @error('name')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="photo">{{ 'Image' }}</label>
                                        <div class="@error('archive.photo') border border-danger rounded-3 @enderror">
                                            <input type="file" class="form-control" id="imageInput" name="photo"
                                                accept="image/*">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="about">{{ 'Description' }}</label>
                                <div class="@error('archive.about') border border-danger rounded-3 @enderror">
                                    <textarea class="form-control" id="about" rows="3" placeholder="Description"
                                        name="description"></textarea>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit"
                                    class="btn bg-gradient-dark btn-md mt-2 mb-2">{{ 'Create Data' }}</button>
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
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">All Data</h5>
                        </div>
                        <div class="d-flex align-items-center">
                            <input type="text" class="form-control form-control-sm me-1" id="searchData"
                                placeholder="Search data..." name="search" style="max-width: 200px;">
                            <span class="input-group-text" onclick="togglePassword('password_confirmation', this)">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-4 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        ID
                                    </th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Photo
                                    </th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Name
                                    </th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Description
                                    </th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Creation Date
                                    </th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Action
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
    function confirmDelete(archiveId, archiveName) {
        Swal.fire({
            title: 'Are you sure delete?',
            html: `Data <strong>${archiveName}</strong> to permenent delete.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete!',
            cancelButtonText: 'Cancel'
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