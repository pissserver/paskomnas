@extends('layouts.user_type.auth')

@section('title', 'Edit Arsip Gambar')

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
            <form action="{{ route('image-archive.update', $archive->id) }}" method="POST" role="form text-left"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="container-fluid py-4">
                    <div class="card">
                        <div class="card-header p-4 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">{{ __('Edit Data Image Archive') }}</h6>
                            <a href="{{ route('image-archive.index') }}"
                                class="btn bg-gradient-danger btn-md mb-0">{{ 'Kembali' }}&nbsp;&nbsp;<i
                                    class="fa fa-arrow-right"></i></a>
                        </div>
                        <div class="card-body pt-1 p-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-control-label">{{ __('Name') }}</label>
                                        <div class="@error('archive.name')border border-danger rounded-3 @enderror">
                                            <input class="form-control" type="text" placeholder="Name" id="user-name"
                                                name="name" value="{{ $archive->name }}">
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
                                            <div class="row">
                                                <div class="col-md-1">
                                                    <img src="{{ asset($archive->photo ?? 'assets/img/default-profile.jpg') }}"
                                                        data-zoom-src="{{ asset($archive->photo ?? 'assets/img/default-profile.jpg') }}"
                                                        class="avatar me-3 zoomable" style="cursor: zoom-in;"
                                                        id="imagePreview">
                                                </div>
                                                <div class="col-md-11">
                                                    <input type="file" id="imageInput" name="photo" accept="image/*"
                                                        class="d-none" onchange="previewSelectedImage(event)">
                                                    <a href="javascript:;"
                                                        onclick="document.getElementById('imageInput').click()"
                                                        class="btn bg-gradient-primary w-100">
                                                        <i class="fa fa-upload pe-2" data-bs-toggle="tooltip"
                                                            title="Upload Image"></i>Upload Image
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="about">{{ 'Description' }}</label>
                                <div class="@error('archive.about') border border-danger rounded-3 @enderror">
                                    <textarea class="form-control" id="about" rows="3" placeholder="Description"
                                        name="description">{{ $archive->description }}</textarea>
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
</div>

<script>
    function previewSelectedImage(event) {
        const input = event.target;
        const preview = document.getElementById('imagePreview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.backgroundImage = '';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        window.activeZoom = mediumZoom('.zoomable', {
            margin: 24,
            background: '#000'
        });
    });
</script>

@endsection