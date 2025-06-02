@foreach ($orderProcesses as $index => $orderProcess)
<tr>
    <td class="text-center">
        <p class="text-xs font-weight-bold mb-0">{{ $index + 1 }}</p>
    </td>
    <td class="text-center">
        <p class="text-xs font-weight-bold mb-0 text-capitalize">{{ $orderProcess->customer->name }}</p>
    </td>
    <td class="text-center">
        <p class="text-xs font-weight-bold mb-0">{{ $orderProcess->order_code }}</p>
    </td>
    <td class="text-center">
        <span class="text-secondary text-xs font-weight-bold">
            {{ \Carbon\Carbon::parse($orderProcess->order_date)->translatedFormat('l, d F Y') }}
        </span>
    </td>
    <td class="text-center">
        <span class="text-white bg-gradient-primary rounded-3 py-2 px-3">
            <i class="fas fa-sync-alt me-2"></i> Diproses
        </span>
    </td>
    <td class="text-center">
        <!-- Tombol Detail -->
        <button type="button"
            class="mb-0 me-2 btn btn-info d-inline-flex align-items-center justify-content-center py-2 px-3"
            data-bs-toggle="modal" data-bs-target="#detailModal-{{ $orderProcess->id }}">
            <i class="fas fa-info-circle text-white me-2"></i> Detail
        </button>

        <!-- Tombol Batalkan -->
        <form id="cancel-form-{{ $orderProcess->id }}" action="{{ route('order.cancel.action', $orderProcess->id) }}"
            method="POST" class="d-none">
            @csrf
            @method('PUT')
        </form>
        <a class="mb-0 btn bg-gradient-danger d-inline-flex align-items-center justify-content-center py-2 px-3"
            href="javascript:;" title="Cancel Order"
            onclick="confirmCancel({{ $orderProcess->id }}, '{{ $orderProcess->order_code }}', '{{ $orderProcess->customer->name }}')">
            <i class="fas fa-ban text-white me-2"></i>Batalkan
        </a>
    </td>
</tr>

<div class="modal fade" id="detailModal-{{ $orderProcess->id }}" tabindex="-1"
    aria-labelledby="detailModalLabel-{{ $orderProcess->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-gradient-info d-flex flex-column align-items-start">
                <h5 class="modal-title text-white mb-1" id="detailModalLabel-{{ $orderProcess->id }}">
                    Detail Order
                </h5>
                <h6 class="text-white">
                    <i class="fa fa-shopping-cart me-2"></i>{{ $orderProcess->order_code }}
                </h6>
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"
                    aria-label="Tutup"></button>
            </div>
            <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                <div class="mb-2">
                    <label class="form-label fw-bold">Nama Customer</label>
                    <input type="text" class="form-control" value="{{ $orderProcess->customer->name }}" readonly>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold">Tanggal Order</label>
                    <input type="text" class="form-control"
                        value="{{ \Carbon\Carbon::parse($orderProcess->order_date)->format('d F Y') }}" readonly>
                </div>
                @foreach ($orderProcess->orderItems as $index => $orderItem)
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
                <a href="{{ route('order.show', ['customer_id' => $orderProcess->customer->id, 'order_id' => $orderProcess->id]) }}"
                    class="btn btn-warning"><i class="fa fa-edit me-2"></i>Edit</a>
            </div>
        </div>
    </div>
</div>
@endforeach