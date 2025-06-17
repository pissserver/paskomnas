@foreach ($logs as $index => $log)
<tr>
    <td class="text-center">
        <p class="text-xs font-weight-bold mb-0">{{ $index + 1 }}</p>
    </td>
    <td class="text-center">
        <span class="text-secondary text-xs font-weight-bold">{{ $log->print_date->format('d F Y') }}</span>
    </td>
    <td class="text-center">
        <a href=""
            class="mb-0 btn bg-gradient-secondary d-inline-flex align-items-center justify-content-center py-2 px-3"
            data-bs-toggle="tooltip" title="Edit barang komoditi">
            <i class="fas fa-edit text-white me-1"></i>Edit
        </a>
        <form id="delete-form-{{ $log->id }}" action="" method="POST" class="d-none">
            @csrf @method('DELETE')
        </form>
        <a class="mb-0 btn bg-gradient-danger d-inline-flex align-items-center justify-content-center py-2 px-3"
            href="javascript:;" title="Delete barang komoditi" onclick="confirmDelete({{ $log->id }})">
            <i class="fas fa-trash-alt text-white me-1"></i>Hapus
        </a>
    </td>
</tr>
@endforeach