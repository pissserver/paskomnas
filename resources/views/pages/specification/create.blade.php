@extends('layouts.user_type.auth')

@section('title', 'Tambah Spesifikasi Komoditi')

@section('content')

<div>
    <form action="{{ route('specification.store') }}" method="POST" role="form text-left" enctype="multipart/form-data">
        <div class="container-fluid">
            @csrf
            @method('PUT')
            <div class="container-fluid py-4">
                <div class="card">
                    <div class="card-header p-4 d-flex justify-content-between">
                        <h6 class="mb-0">{{ __('Tambahkan Spesifikasi Komoditi') }}</h6>
                        <a href="{{ route('specification.index') }}"
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
                                    <label for="specification-name"
                                        class="form-control-label">{{ __('Spesifikasi Komoditi') }}</label>
                                    <div class="@error('specification.name')border border-danger rounded-3 @enderror">
                                        <input class="form-control" type="text" placeholder="Spesifikasi Komoditi"
                                            id="specification-name" name="name">
                                        @error('name')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
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
</script>
@endsection