@extends('layouts.user_type.auth')

@section('title', 'Edit Customer Group')

@section('content')


<div class="container-fluid py-4">
    <form action="{{ route('customer.group.update', $customerGroup->id) }}" method="POST" role="form text-left"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-header pb-0 p-4 d-flex justify-content-between">
                <h6 class="mb-0">{{ __('Data Customer') }}</h6>

                <a href="{{ route('customer.index') }}"
                    class="btn bg-gradient-danger btn-md mb-0">{{ 'Kembali' }}&nbsp;&nbsp;<i
                        class="fa fa-arrow-right"></i></a>
            </div>
            <div class="card-body pt-4 p-4">
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
                                <input class="form-control" type="text" placeholder="Nama Customer Group" id="name"
                                    name="name" value="{{ old('name', $customerGroup) }}">
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
                                        style="border: 2px dashed #DE0A93; border-radius: 8px; width: 43px; height: 43px; background-color: #ffffff; background-position: center; background-size: cover; background-repeat: no-repeat;
                                        background-image: url('{{ asset($customerGroup ->photo ?? 'assets/img/default-profile.jpg') }}');">
                                    </div>

                                    <input type="file" id="imageInput" name="photo" accept="image/*" class="d-none"
                                        onchange="previewSelectedImage(event)">

                                    <div class="flex-grow-1 ms-3 pt-3">
                                        <a href="javascript:;" onclick="document.getElementById('imageInput').click()"
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
                        class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ 'Update Data' }}&nbsp;&nbsp;<i
                            class="fa fa-save"></i></button>
                </div>
            </div>
        </div>
    </form>
</div>


@include('components.modal.crop-image-modal')

<script>
    let cropper;
    const input = document.getElementById('imageInput');
    const preview = document.getElementById('imagePreview');
    const cropImage = document.getElementById('cropperImage');
    const cropModal = new bootstrap.Modal(document.getElementById('cropModal'));

    function previewSelectedImage(event) {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            cropImage.src = e.target.result;
            cropModal.show();
        };
        reader.readAsDataURL(file);
    }

    document.getElementById('cropModal').addEventListener('shown.bs.modal', function () {
        cropper = new Cropper(cropImage, {
            aspectRatio: 1,
            viewMode: 1,
            responsive: true,
        });
    });

    document.getElementById('cropModal').addEventListener('hidden.bs.modal', function () {
        cropper.destroy();
        cropper = null;
    });

    document.getElementById('cropButton').addEventListener('click', function () {
        const canvas = cropper.getCroppedCanvas({
            width: 200,
            height: 200,
        });

        canvas.toBlob(function (blob) {
            const url = URL.createObjectURL(blob);
            preview.style.backgroundImage = `url('${url}')`;

            // Jika ingin submit ke server, simpan blob di FormData
            const file = new File([blob], "cropped.jpg", { type: "image/jpeg" });

            // Gantikan file asli agar bisa dikirim lewat form biasa
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            input.files = dataTransfer.files;

            cropModal.hide();
        }, 'image/jpeg');
    });

    flatpickr(".datepicker", {
        locale: "id",
        altInput: true,
        altFormat: "l, d F Y",
        dateFormat: "Y-m-d",
        onReady: function (selectedDates, dateStr, instance) {
            if (instance.altInput) {
                instance.altInput.setAttribute("style", "background-color: white; cursor: text;");
            }
        }
    });
</script>

@endsection