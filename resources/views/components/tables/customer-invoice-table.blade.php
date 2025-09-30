@foreach ($orders as $index => $order)
<tr>
    <td class="text-center">
        <p class="text-xs font-weight-bold mb-0">{{ $index + 1 }}</p>
    </td>
    <td class="text-center">
        <p class="text-xs font-weight-bold mb-0">{{ $order->order_code }}</p>
    </td>
    <td class="text-center">
        <span
            class="text-secondary text-xs font-weight-bold text-capitalize">{{ \Carbon\Carbon::parse($order->order_date)->translatedFormat('l, d F Y') }}</span>
    </td>
    <td class="text-center">
        <a href="{{ route('order.show', ['customer_id' => $order->customer_id, 'order_id' => $order->id]) }}"
            class="mb-0 btn bg-gradient-info d-inline-flex align-items-center justify-content-center py-2 px-3"
            data-bs-toggle="tooltip" title="Edit order">
            <i class="fas fa-eye text-white"></i>
        </a>
    </td>
    <td class="text-center">
        <span class="text-secondary text-xs font-weight-bold">
            Rp {{ number_format($order->total_with_ppn, 0, ',', '.') }}
        </span>
    </td>
</tr>
@endforeach
<tr>
    <td colspan="4" class="text-end">
        <h5 class="text-xs text-secondary fw-bold text-uppercase mb-0">
            Total Tagihan Keseluruhan :
        </h5>
    </td>
    <td class="text-center">
        <span class="text-white bg-gradient-success text-xs fw-bold text-capitalize px-3 py-2 rounded-pill">
            <i class="fas fa-money-bill-wave text-white me-1"></i>
            Rp {{ number_format($totalAll, 0, ',', '.') }}
        </span>
    </td>
</tr>