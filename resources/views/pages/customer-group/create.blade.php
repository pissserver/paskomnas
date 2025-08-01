@extends('layouts.user_type.auth')

@section('title', 'Tambah Customer Baru')

@section('content')

<div>
    <form action="{{ route('customer.group.store') }}" method="POST" role="form text-left"
        enctype="multipart/form-data">
        <div class="container-fluid">
            @csrf
            @method('PUT')
            <div class="container-fluid py-4">
                <div class="card">
                    <div class="card-header p-4 d-flex justify-content-between">
                        <h6 class="mb-0">{{ __('Customer Group Baru') }}</h6>
                        <a href="{{ route('customer.group.index') }}"
                            class="btn bg-gradient-danger btn-md mb-0">{{ 'Kembali' }}&nbsp;&nbsp;<i
                                class="fa fa-arrow-right"></i></a>
                    </div>
                    <div class="card-body pt-2 p-4">
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
                                {{ session('success') }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                <i class="fa fa-close" aria-hidden="true"></i>
                            </button>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Nama Customer Group</label>
                                    <div class="@error('name')border border-danger rounded-3 @enderror">
                                        <input class="form-control" type="text" placeholder="Nama Customer Group"
                                            id="name" name="name" value="{{ old('name') }}">
                                        @error('name')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Logo Customer Group --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="@error('photo') border border-danger rounded-3 @enderror">
                                        <div class="d-flex align-items-center">
                                            <div id="imagePreview" class="zoomable"
                                                style="border: 2px dashed #DE0A93; border-radius: 8px; width: 43px; height: 43px; background-color: #ffffff; background-position: center; background-size: cover; background-repeat: no-repeat;">
                                            </div>

                                            <input type="file" id="imageInput" name="photo" accept="image/*"
                                                class="d-none" onchange="previewSelectedImage(event)">

                                            <div class="flex-grow-1 ms-3 pt-3">
                                                <a href="javascript:;"
                                                    onclick="document.getElementById('imageInput').click()"
                                                    class="btn bg-gradient-primary w-100">
                                                    <i class="fa fa-image pe-2" data-bs-toggle="tooltip"
                                                        title="Upload Image"></i>Unggah
                                                    Logo
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit"
                                class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ 'Simpan' }}&nbsp;&nbsp;<i
                                    class="fa fa-save"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@include('components.modal.crop-image-modal')

<script>
    let cropper;
    const cropperImage = document.getElementById('cropperImage');
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');
    const cropModal = new bootstrap.Modal(document.getElementById('cropModal'));

    function previewSelectedImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                cropperImage.src = e.target.result;
                cropModal.show();

                // Inisialisasi cropper setelah modal muncul
                setTimeout(() => {
                    if (cropper) cropper.destroy();
                    cropper = new Cropper(cropperImage, {
                        aspectRatio: 1,
                        viewMode: 1,
                        autoCropArea: 1,
                        background: false,
                        responsive: true,
                    });
                }, 300);
            };
            reader.readAsDataURL(file);
        }
    }

    document.getElementById('cropButton').addEventListener('click', () => {
        const canvas = cropper.getCroppedCanvas({
            width: 300,
            height: 300,
        });

        canvas.toBlob(blob => {
            const url = URL.createObjectURL(blob);
            imagePreview.style.backgroundImage = `url('${url}')`;

            // Replace input file dengan blob hasil crop
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(new File([blob], 'cropped.jpg', { type: 'image/jpeg' }));
            imageInput.files = dataTransfer.files;

            cropModal.hide();
        }, 'image/jpeg');
    });
</script>
@endsection