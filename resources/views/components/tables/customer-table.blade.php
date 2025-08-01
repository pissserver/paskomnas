@foreach ($customers as $index => $customer)
<tr>
    <td class="text-center">
        <p class="text-xs font-weight-bold mb-0">{{ $index + 1 }}</p>
    </td>
    <td class="text-center">
        <img src="{{ asset($customer->photo ?? 'assets/img/default-profile.jpg') }}"
            data-zoom-src="{{ asset($customer->photo ?? 'assets/img/default-profile.jpg') }}"
            class="avatar me-3 zoomable" style="cursor: zoom-in;">
    </td>
    <td class="text-center">
        @if ($customer->customer_group_id != null)
        <p
            class="text-xs font-weight-bold mb-0 text-capitalize bg-gradient-warning text-white rounded rounded-pill px-2 py-1 m-0 d-inline-block">
            <i class="fa fa-users me-2"></i>{{ $customer->customerGroup->name }}
        </p>
        @endif
        <p class="text-xs font-weight-bold mb-0 text-capitalize">{{ $customer->name }}</p>
    </td>
    <td class="text-center text-wrap" style="max-width: 250px;">
        <p class="text-xs font-weight-bold mb-0">{{ $customer->company_address }}</p>
    </td>
    <td class="text-center">
        <span class="text-secondary text-xs font-weight-bold">{{ $customer->npwp }}</span>
    </td>
    <td class="text-center">

        <a href="{{ route('customer.edit', $customer->id) }}"
            class="mb-0 btn bg-gradient-info d-inline-flex align-items-center justify-content-center py-2 px-3"
            data-bs-toggle="tooltip" title="Detail customer">
            <i class="fas fa-info-circle text-white me-1"></i>Detail
        </a>
        <form id="delete-form-{{ $customer->id }}" action="{{ route('customer.destroy', $customer->id) }}" method="POST"
            class="d-none">
            @csrf @method('DELETE')
        </form>
        <a class="mb-0 btn bg-gradient-danger d-inline-flex align-items-center justify-content-center py-2 px-3"
            href="javascript:;" title="Delete customer"
            onclick="confirmDelete({{ $customer->id }}, '{{ $customer->name }}')">
            <i class="fas fa-trash-alt text-white me-1"></i>Hapus
        </a>
    </td>
</tr>
@endforeach