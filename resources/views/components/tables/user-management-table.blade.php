@foreach ($users as $index => $user)
<tr>
    <td class="text-center">
        <p class="text-xs font-weight-bold mb-0">{{ $index + 1 }}</p>
    </td>
    <td class="text-center">
        <img src="{{ asset($user->photo ?? 'assets/img/default-profile.jpg') }}"
            data-zoom-src="{{ asset($user->photo ?? 'assets/img/default-profile.jpg') }}" class="avatar me-3 zoomable"
            style="cursor: zoom-in;">
    </td>
    <td class="text-center">
        <p class="text-xs font-weight-bold mb-0 text-capitalize">{{ $user->name }}</p>
    </td>
    <td class="text-center">
        <p class="text-xs font-weight-bold mb-0">{{ $user->email }}</p>
    </td>
    <td class="text-center">
        <span class="mb-0 btn btn-sm {{ $user->role == 'master' ? 'bg-gradient-primary' : 'bg-gradient-warning' }}">
            {{ $user->role }}
        </span>
    </td>

    <td class="text-center">
        <span class="text-secondary text-xs font-weight-bold">{{ $user->created_at->format('d F Y') }}</span>
    </td>
    <td class="text-center">
        @if ($user->id === Auth::id())
        <span class="mb-0 btn bg-gradient-info" style="cursor: default !important;">
            Anda <i class="fas fa-user-circle ms-1"></i>
        </span>
        @elseif ($user->is_online)
        <span class="mb-0 btn bg-gradient-success" style="cursor: default !important;">
            Sedang Aktif <i class="fas fa-check-circle ms-1"></i>
        </span>
        @else
        <a href="{{ route('user-management.edit', $user->id) }}"
            class="mb-0 btn bg-gradient-secondary d-inline-flex align-items-center justify-content-center py-2 px-3"
            data-bs-toggle="tooltip" title="Edit user">
            <i class="fas fa-edit text-white me-1"></i>Edit
        </a>
        <form id="delete-form-{{ $user->id }}" action="{{ route('user-management.destroy', $user->id) }}" method="POST"
            class="d-none">
            @csrf @method('DELETE')
        </form>
        <a class="mb-0 btn bg-gradient-danger d-inline-flex align-items-center justify-content-center py-2 px-3"
            href="javascript:;" title="Delete user" onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')">
            <i class="fas fa-trash-alt text-white me-1"></i>Hapus
        </a>
        @endif

    </td>
</tr>
@endforeach