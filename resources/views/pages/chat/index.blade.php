@extends('layouts.user_type.auth')

@section('title', 'Chat Paskomnas || PISS')

@section('content')
<div class="container-fluid card py-5 mb-5">
    <div class="row" style="height: 600px;">
        <div class="col-4 border-end overflow-auto">
            <h5>Kontak</h5>
            <ul class="list-group mt-3" id="user-list">
                @foreach ($users as $user)
                @php
                $lastMessage = $user->lastMessageWith($currentUserId);
                @endphp
                <li class="list-group-item user-item" data-id="{{ $user->id }}" data-name="{{ $user->name }}"
                    data-photo="{{ asset($user->photo ? $user->photo : 'assets/img/default-profile.jpg') }}">

                    <div class="d-flex align-items-center">
                        <img src="{{ asset($user->photo ? $user->photo : 'assets/img/default-profile.jpg') }}"
                            class="avatar me-3" width="40" height="40" style="object-fit: cover; border-radius: 50%;">
                        <div class="flex-grow-1">
                            <div class="fw-bold text-dark">{{ $user->name }}</div>
                            <div class="text-muted small text-truncate" style="max-width: 200px;">
                                {{ $lastMessage ? $lastMessage->message : 'Belum ada pesan.' }}
                            </div>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>

        <div class="col-8 d-flex flex-column" style="height: 65vh;">
            {{-- Nama Kontak (Sticky Header) --}}
            <div id="contact-name" class="py-2 px-3 border-bottom fw-bold bg-white d-flex align-items-center"
                style="position: sticky; top: 0; z-index: 10;">
                <span class="text-muted">Pilih kontak untuk mulai chat</span>
            </div>

            {{-- Kotak chat scrollable --}}
            <div class="flex-grow-1 overflow-auto" id="chat-box" style="padding: 10px; min-height: 0;">
                {{-- Pesan akan ditambahkan di sini --}}
            </div>

            {{-- Form kirim pesan --}}
            <form id="chat-form" class="d-flex mt-2 align-items-center p-2 border-top">
                <input type="hidden" id="receiver_id">

                <input type="text" id="message" class="form-control me-2" placeholder="Tulis pesan..."
                    style="border-radius: 1rem;">

                <button type="submit" class="btn btn-primary mt-3" style="border-radius: 1rem;">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    let selectedUserId = null;
    let currentUserId = {{ auth()->id() }};
    let refreshInterval = null;

    // Event: Klik kontak user
    $(document).on('click', '.user-item', function () {
        selectedUserId = $(this).data('id');
        const contactName = $(this).data('name');
        const contactPhoto = $(this).data('photo');

        $('#receiver_id').val(selectedUserId);
        $('#contact-name').html(`
            <img src="${contactPhoto}" width="32" height="32" class="me-2 rounded-circle" style="object-fit: cover;">
            ${contactName}
        `);
        loadMessages();
    });

    // Fungsi: Load pesan via AJAX GET
    function loadMessages() {
        if (!selectedUserId) return;

        $.get('{{ url("chat/messages") }}/' + selectedUserId, function (data) {
            $('#chat-box').html('');
            data.forEach(function (msg) {
                const align = msg.sender_id === currentUserId ? 'text-end' : 'text-start';
                const bubble = `<div class="${align} mb-2">
                    <span class="px-3 py-2 d-inline-block rounded-3 bg-light"
                    style="max-width: 75%; white-space: pre-wrap; word-break: break-word;">${msg.message}</span>
                </div>`;
                $('#chat-box').append(bubble);
            });

            // Auto scroll ke bawah
            $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
        }).fail(function (xhr) {
            console.error(xhr.responseText);
            alert("Gagal memuat pesan.");
        });
    }

    // Event: Submit form chat
    $('#chat-form').on('submit', function (e) {
        e.preventDefault();

        const message = $('#message').val().trim();
        const receiverId = $('#receiver_id').val();

        if (!receiverId || !message) return;

        $.post('{{ url("chat/messages") }}', {
            _token: '{{ csrf_token() }}',
            message: message,
            receiver_id: receiverId
        }, function (data) {
            $('#message').val('');
            loadMessages();
        });
    });

    // Auto-refresh setiap 3 detik jika ada user dipilih
    setInterval(function () {
        if (selectedUserId) {
            loadMessages();
        }
    }, 2000);
</script>

@endsection