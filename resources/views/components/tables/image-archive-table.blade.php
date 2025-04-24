@foreach ($archives as $index => $archive)
<tr>
    <td class="ps-4">
        <p class="text-xs font-weight-bold mb-0">{{ $index + 1 }}</p>
    </td>
    <td>
        <img src="{{ asset($archive->photo ?? 'assets/img/default-profile.jpg') }}"
            data-zoom-src="{{ asset($archive->photo ?? 'assets/img/default-profile.jpg') }}"
            class="avatar me-3 zoomable" style="cursor: zoom-in;">
    </td>
    <td class="text-center">
        <p class="text-xs font-weight-bold mb-0">{{ $archive->name }}</p>
    </td>
    <td class="text-left">
        <p class="text-xs font-weight-bold mb-0">{{ $archive->description ?? 'No Description' }}</p>
    </td>
    <td class="text-center">
        <span class="text-secondary text-xs font-weight-bold">{{ $archive->created_at->format('d F Y') }}</span>
    </td>
    <td class="text-center">
        <a href="{{ route('image-archive.edit', $archive->id) }}"
            class="mx-1 mb-0 btn bg-gradient-secondary d-inline-flex align-items-center justify-content-center"
            data-bs-toggle="tooltip" title="Edit Data" style="width: 35px; height: 35px; border-radius: 7px;">
            <i class="fas fa-edit text-white m-auto fa-lg"></i>
        </a>
        <form id="delete-form-{{ $archive->id }}" action="{{ route('image-archive.destroy', $archive->id) }}"
            method="POST" class="d-none">
            @csrf @method('DELETE')
        </form>
        <a class="mx-1 mb-0 btn bg-gradient-danger d-inline-flex align-items-center justify-content-center"
            style="width: 35px; height: 35px; border-radius: 7px;" href="javascript:;" title="Delete Data"
            onclick="confirmDelete({{ $archive->id }}, '{{ $archive->name }}')">
            <i class="fas fa-trash-alt text-white m-auto fa-lg"></i>
        </a>
    </td>
</tr>
@endforeach