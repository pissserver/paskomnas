@foreach ($users as $index => $user)
<tr>
    <td class="ps-4">
        <p class="text-xs font-weight-bold mb-0">{{ $index + 1 }}</p>
    </td>
    <td>
        <img src="{{ asset($user->photo ?? 'assets/img/default-profile.jpg') }}"
            data-zoom-src="{{ asset($user->photo ?? 'assets/img/default-profile.jpg') }}" class="avatar me-3 zoomable"
            style="cursor: zoom-in;">
    </td>
    <td class="text-center">
        <p class="text-xs font-weight-bold mb-0">{{ $user->name }}</p>
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
        @if ($user->id != Auth::id())
        <a href="{{ route('user-management.edit', $user->id) }}"
            class="mx-1 mb-0 btn bg-gradient-secondary d-inline-flex align-items-center justify-content-center"
            data-bs-toggle="tooltip" title="Edit user" style="width: 35px; height: 35px; border-radius: 7px;">
            <i class="fas fa-user-edit text-white m-auto fa-lg"></i>
        </a>
        <form id="delete-form-{{ $user->id }}" action="{{ route('user-management.destroy', $user->id) }}" method="POST"
            class="d-none">
            @csrf @method('DELETE')
        </form>
        <a class="mx-1 mb-0 btn bg-gradient-danger d-inline-flex align-items-center justify-content-center"
            style="width: 35px; height: 35px; border-radius: 7px;" href="javascript:;" title="Delete user"
            onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')">
            <i class="fas fa-trash-alt text-white m-auto fa-lg"></i>
        </a>

        @else
        <span class="mb-0 btn btn-sm bg-gradient-success" style="cursor: default !important;">Logged In</span>
        @endif
    </td>
</tr>
@endforeach