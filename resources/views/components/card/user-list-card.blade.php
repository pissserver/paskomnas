@foreach ($users as $user)
@php
$lastMessage = $user->lastMessage;
$isUnread = $lastMessage && $lastMessage->sender_id == $user->id && !$lastMessage->is_read;
@endphp
<li class="list-group-item user-item cursor-pointer mb-n4 pt-3" style="background-color: #1d1d1d;"
    data-id="{{ $user->id }}" data-name="{{ $user->name }}"
    data-photo="{{ asset($user->photo ? $user->photo : 'assets/img/default-profile.jpg') }}">

    <div class="d-flex align-items-center">
        <img src="{{ asset($user->photo ? $user->photo : 'assets/img/default-profile.jpg') }}" class="avatar me-3"
            width="40" height="40" style="object-fit: cover; border-radius: 50%;">
        <div class="flex-grow-1">
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-bold text-white">{{ $user->name }}</span>
                @if($user->unreadCount > 0)
                <span class="badge bg-danger rounded-pill">{{ $user->unreadCount }}</span>
                @endif
            </div>
            <div class="small text-truncate {{ $isUnread ? 'text-secondary fw-bold' : 'text-muted' }}"
                style="max-width: 300px;">
                @if ($lastMessage && $lastMessage->sender_id === $currentUserId)
                <i class="fas fa-check-double me-1 {{ $lastMessage->is_read ? 'text-info' : 'text-secondary' }}"></i>
                @endif

                @if ($lastMessage)
                @if ($lastMessage->is_file)
                @php
                $ext = pathinfo($lastMessage->file_name, PATHINFO_EXTENSION);
                $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']);
                @endphp

                @if ($isImage)
                <i class="fas fa-camera me-1"></i> Foto
                @else
                <i class="fas fa-paperclip me-1"></i> File
                @endif
                @else
                {{ $lastMessage->message }}
                @endif
                @else
                Belum ada pesan.
                @endif
            </div>
        </div>
    </div>
    <hr>
</li>
@endforeach