@foreach ($cancelOrders as $index => $cancelOrder)
<tr>
    <td class="text-center">
        <p class="text-xs font-weight-bold mb-0">{{ $index + 1 }}</p>
    </td>
    <td class="text-center">
        <p class="text-xs font-weight-bold mb-0 text-capitalize">{{ $cancelOrder->customer->name }}</p>
    </td>
    <td class="text-center">
        <p class="text-xs font-weight-bold mb-0">{{ $cancelOrder->order_code }}</p>
    </td>
    <td class="text-center">
        <span class="text-secondary text-xs font-weight-bold">
            {{ \Carbon\Carbon::parse($cancelOrder->order_date)->translatedFormat('l, d F Y') }}
        </span>
    </td>
    <td class="text-center">
        <span class="text-white bg-primary rounded-3 py-2 px-3">
            <i class="fas fa-sync-alt me-2"></i> Diproses
        </span>
    </td>
    <td class="text-center">
        <!-- Tombol Detail -->
        <button type="button"
            class="mb-0 me-2 btn btn-info d-inline-flex align-items-center justify-content-center py-2 px-3"
            data-bs-toggle="modal" data-bs-target="#detailModal-{{ $cancelOrder->id }}">
            <i class="fas fa-info-circle text-white me-2"></i> Detail
        </button>

        <!-- Tombol Batalkan -->
        <form id="update-form-{{ $cancelOrder->id }}" action="{{ route('cancel.order.action', $cancelOrder->id) }}"
            method="POST" class="d-none">
            @csrf
            @method('PUT')
        </form>
        <a class="mb-0 btn bg-gradient-danger d-inline-flex align-items-center justify-content-center py-2 px-3"
            href="javascript:;" title="Cancel Order"
            onclick="confirmUpdate({{ $cancelOrder->id }}, '{{ $cancelOrder->order_code }}', '{{ $cancelOrder->customer->name }}')">
            <i class="fas fa-ban text-white me-2"></i>Batalkan
        </a>
    </td>
</tr>

<div class="modal fade" id="detailModal-{{ $cancelOrder->id }}" tabindex="-1"
    aria-labelledby="detailModalLabel-{{ $cancelOrder->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-gradient-info d-flex flex-column align-items-start">
                <h5 class="modal-title text-white mb-1" id="detailModalLabel-{{ $cancelOrder->id }}">
                    Detail Order
                </h5>
                <h6 class="text-white">
                    <i class="fa fa-shopping-cart me-2"></i>{{ $cancelOrder->order_code }}
                </h6>
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"
                    aria-label="Tutup"></button>
            </div>
            <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                <div class="mb-2">
                    <label class="form-label fw-bold">Nama Customer</label>
                    <input type="text" class="form-control" value="{{ $cancelOrder->customer->name }}" readonly>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold">Tanggal Order</label>
                    <input type="text" class="form-control"
                        value="{{ \Carbon\Carbon::parse($cancelOrder->order_date)->format('d F Y') }}" readonly>
                </div>
                @foreach ($cancelOrder->orderItems as $index => $orderItem)
                <div class="border rounded p-3 mb-3 shadow-sm bg-light">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-4">
                            <label class="form-label">Barang Komoditi</label>
                            <input type="text" class="form-control" value="{{ $orderItem->goods->name }}" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Satuan</label>
                            <input type="text" class="form-control" value="{{ $orderItem->goods->unit }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Harga Satuan</label>
                            <input type="text" class="form-control"
                                value="Rp {{ number_format($orderItem->price, 0, ',', '.') }}" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Jumlah</label>
                            <input type="text" class="form-control" value="{{ $orderItem->quantity }}" readonly>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach