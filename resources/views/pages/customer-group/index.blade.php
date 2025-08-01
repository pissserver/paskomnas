@extends('layouts.user_type.auth')

@section('title', 'Customer Group Paskomnas || PISS')

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
        <div class="col-xl-12 col-sm-12 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Daftar Customer Group</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ $totalCustomerGroups }}
                                    <span class="text-success text-sm font-weight-bolder">Customer Group</span>
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <a href="{{ route('customer.group.create') }}" class="btn bg-gradient-primary mb-0 mt-1"
                                type="button">Tambah Group
                                Baru
                                &nbsp;&nbsp; <i class="fa fa-plus"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-n2 p-4">
        @foreach($customerGroups as $customerGroup)
        <div class="col-md-3 mt-4">
            <div class="card ">
                <div class="card-header mx-4 p-3 text-center">
                    <img src="{{ $customerGroup->photo ?? asset('assets/img/default-profile.jpg') }}"
                        alt="{{ $customerGroup->name }}" class="avatar shadow-lg img-fluid"
                        style="width: 80px; height: 80px; object-fit: cover;">
                </div>

                <div class="card-body pt-0 p-3 text-center">
                    <h5 class="text-center mb-0 text-uppercase"><strong>{{ $customerGroup->name }}</strong></h5>
                    <hr class="horizontal dark mt-2 mb-3">
                    @if ($customerGroup->customers->count() > 0)
                    <p>Member of group</p>
                    <div class="d-flex flex-wrap justify-content-center gap-1">
                        @foreach ($customerGroup->customers as $customer)
                        <a href="{{ route('customer.edit', $customer->id) }}"
                            class="avatar avatar-sm rounded-circle me-1 mb-1" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="{{ $customer->name }}">
                            <img alt="Image placeholder"
                                src="{{ $customer->photo ? asset($customer->photo) : asset('assets/img/default-profile.jpg') }}">
                        </a>
                        @endforeach
                    </div>
                    @else
                    <p>Belum ada member</p>
                    @endif
                    <hr class="horizontal dark mt-2 mb-3">
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('customer.group.edit', $customerGroup->id) }}" class="btn btn-info">
                            <i class="fas fa-edit me-2"></i> Edit
                        </a>
                        <form id="delete-form-{{ $customerGroup->id }}"
                            action="{{ route('customer.group.destroy', $customerGroup->id) }}" method="POST"
                            class="d-none">
                            @csrf @method('DELETE')
                        </form>
                        <a class="btn bg-danger text-white" href="javascript:;" title="Delete barang komoditi"
                            onclick="confirmDelete({{ $customerGroup->id }}, '{{ $customerGroup->name }}')">
                            <i class="fas fa-trash-alt me-2 text-white"></i> Hapus
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
    function confirmDelete(customerGroupsId, customerGroupsName) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            html: `Customer Group <strong>${customerGroupsName}</strong> akan dihapus permanen.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${customerGroupsId}`).submit();
            }
        });
    }
</script>

@endsection