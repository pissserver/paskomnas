@foreach ($logs as $index => $log)
<tr>
    <td class="text-center">
        <p class="text-xs font-weight-bold mb-0">{{ $index + 1 }}</p>
    </td>
    <td class="text-center">
        <span class="text-secondary text-xs font-weight-bold">{{ $log->print_date->format('d F Y') }}</span>
    </td>
    <td class="text-center">
        @if ($log->customer_group_id != null)
        <span class="badge bg-info text-white">Group</span>
        @else
        <span class="badge bg-success text-white">Trading</span>
        @endif
    </td>

    <td class="text-center">
        <a href="javascript:void(0)"
            class="btn-print-ulang mb-0 btn bg-gradient-primary d-inline-flex align-items-center justify-content-center py-2 px-3"
            data-log-id="{{ $log->id }}">
            <i class="fas fa-print me-2"></i>Print Ulang
        </a>
    </td>
</tr>
@endforeach