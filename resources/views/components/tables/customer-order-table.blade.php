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
        <span class="text-secondary text-xs font-weight-bold text-capitalize">
            @if ($order->status === 'confirmed')
            <span class="text-white bg-primary rounded-3 py-2 px-3">
                <i class="fas fa-sync-alt me-2"></i> Diproses
            </span>
            @elseif ($order->status === 'done')
            <span class="text-white bg-success rounded-3 py-2 px-3">
                <i class="fas fa-check-circle me-2"></i> Selesai
            </span>
            @elseif ($order->status === 'cancelled')
            <span class="text-white bg-danger rounded-3 py-2 px-3">
                <i class="fas fa-times-circle me-2"></i> Dibatalkan
            </span>
            @endif
        </span>
    </td>
    <td class="text-center">
        <a href="{{ route('order.show', ['customer_id' => $order->customer_id, 'order_id' => $order->id]) }}"
            class="mb-0 btn bg-gradient-info d-inline-flex align-items-center justify-content-center py-2 px-3"
            data-bs-toggle="tooltip" title="Edit order">
            <i class="fas fa-eye text-white"></i>
        </a>
    </td>
</tr>
@endforeach