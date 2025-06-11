@extends('layouts.user_type.auth')

@section('title', 'Edit Barang Komoditi')

@section('content')

<div>
    <form action="{{ route('goods.update', $goods->id) }}" method="POST" role="form text-left"
        enctype="multipart/form-data">
        <div class="container-fluid">
            @csrf
            @method('PUT')
            <div class="container-fluid py-4">
                <div class="card">
                    <div class="card-header pb-0 p-4 d-flex justify-content-between">
                        <h6 class="mb-0">{{ __('Data Barang Komoditi') }}</h6>

                        <a href="{{ route('goods.index') }}"
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="goods-name"
                                        class="form-control-label">{{ __('Nama Barang Komoditi') }}</label>
                                    <div class="@error('goods.name')border border-danger rounded-3 @enderror">
                                        <input class="form-control" type="text" placeholder="Nama Barang Komoditi"
                                            id="goods-name" name="name" value="{{ $goods->name }}">
                                        @error('name')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="unit" class="form-control-label">{{ __('Satuan') }}</label>
                                    <div class="@error('unit') border border-danger rounded-3 @enderror">
                                        <input class="form-control" type="text" placeholder="Satuan" id="unit"
                                            name="unit" value="{{ $goods->unit }}">
                                        @error('unit')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ppn" class="form-control-label">{{ __('PPN') }}</label>
                                    <div class="@error('ppn') border border-danger rounded-3 @enderror">
                                        <input class="form-control" type="number" placeholder="0" id="ppn" name="ppn"
                                            value="{{ $goods->ppn }}" step="0.01">
                                        @error('ppn')
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