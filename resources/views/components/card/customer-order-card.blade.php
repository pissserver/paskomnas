@if($customers->isEmpty())
<div class="empty-data w-100">
    <div class="text-center">
        <img src="{{ asset('assets/img/empty-data.png') }}" alt="No Data" class="img-fluid" width="450">
        <h4 class="text-secondary">Customer yang dicari tidak ada</h4>
    </div>
</div>
@else
@foreach($customers as $customer)
<div class="col-md-3 mt-4">
    <div class="card">
        <div class="card-header mx-4 p-3 position-relative">
            <!-- Kotak PO ID di kiri atas -->
            <div class="position-absolute top-0 start-0 translate-middle-y mt-2 ms-2">
                <div class="bg-gradient-primary text-white px-3 py-1 rounded" style="font-size: 14px;">
                    <b>PO{{ str_pad($customer->id, 2, '0', STR_PAD_LEFT) }}</b>
                </div>
            </div>

            <!-- Gambar di tengah -->
            <div class="text-center mt-3">
                <img src="{{ $customer->photo ?? asset('assets/img/default-profile.jpg') }}" alt="{{ $customer->name }}"
                    class="avatar shadow-lg img-fluid" style="width: 80px; height: 80px; object-fit: cover;">
            </div>
        </div>
        @php
        $orderCount = $customer->orders_count;

        if ($orderCount >= 500) {
        $level = 'Platinum';
        $badgeClass = 'bg-gradient-dark';
        } elseif ($orderCount >= 100) {
        $level = 'Gold';
        $badgeClass = 'bg-gradient-warning';
        } elseif ($orderCount >= 20) {
        $level = 'Silver';
        $badgeClass = 'bg-gradient-secondary';
        } else {
        $level = 'Basic';
        $badgeClass = 'bg-gradient-light text-dark';
        }
        @endphp
        <div class="card-body pt-0 p-3 text-center">
            @if ($customer->customer_group_id != null)
            <p
                class="text-xs font-weight-bold mb-0 text-capitalize bg-gradient-warning text-white rounded rounded-pill px-2 py-1 m-0 d-inline-block">
                <i class="fa fa-users me-2"></i>{{ $customer->customerGroup->name }}
            </p>
            @endif
            <h6 class="text-center mb-0">{{ $customer->name }}</h6>
            <div class="d-flex justify-content-center mb-2">
                <span class="badge {{ $badgeClass }} px-3 mt-2 py-1 rounded-pill text-uppercase small">
                    <i class="fa fa-award me-1"></i>
                    {{ $level }}
                </span>
            </div>
            <span class="text-xs d-inline-block" style="line-height: 1.5;">{{ $customer->company_address }}</span>
            <hr class="horizontal dark mt-2 mb-3">
            <div class="d-flex justify-content-center gap-2">
                <a href="{{ route('order.create', $customer->id) }}" class="btn  btn-primary">
                    <i class="fas fa-shopping-cart me-2"></i> Order
                </a>
                <a href="{{ route('customer.edit', $customer->id) }}" class="btn  btn-info">
                    <i class="fas fa-info-circle me-2"></i> Detail
                </a>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif