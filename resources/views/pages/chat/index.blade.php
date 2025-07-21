@extends('layouts.user_type.auth')

@section('title', 'Chat Paskomnas || PISS')

@section('content')
<div class="container-fluid card py-3 mb-3" id="chat-container">
    <div class="row" style="height: 700px;">
        <div class="col-4 border-end overflow-auto">
            <h5 class="text-white">Kontak</h5>
            <ul class="list-group mt-3" id="user-list">
                @include('components.card.user-list-card', ['users' => $users, 'currentUserId' => $currentUserId])
            </ul>
        </div>

        <div class="col-8 d-flex flex-column h-100" id="chat-area">
            {{-- Akan diisi dinamis lewat JavaScript --}}
            <div class="empty-data text-center m-auto" id="chat-placeholder">
                <img src="{{ asset('assets/img/select-contact.png') }}" width="450">
                <h4 class="text-white">Pilih kontak untuk memulai chat</h4>
            </div>
        </div>
    </div>
</div>

<style>
    #chat-container {
        position: relative;
        background-image: url("{{ asset('assets/img/chat-background.jpg') }}");
        background-size: cover;
        background-repeat: repeat;
        background-position: center;
        overflow: hidden;
        border-radius: 1rem;
    }

    /* Overlay transparan + blur */
    #chat-container::before {
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background-color: rgba(0, 0, 0, 0.8);
        z-index: 0;
        border-radius: 1rem;
    }

    /* Pastikan konten berada di atas overlay */
    #chat-container>* {
        position: relative;
        z-index: 1;
    }

    #chat-box {
        overflow-y: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    #chat-box::-webkit-scrollbar {
        display: none;
    }
</style>

<script>
    let selectedUserId = null;
    let currentUserId = {{ auth()->id() }};
    let refreshInterval = null;

    $(document).ready(function () {
        // Event delegation: tombol upload
        $(document).on('click', '#upload-btn', function () {
            console.log("Upload diklik");
            $('#file-input').click();
        });

        // Event delegation: ketika file dipilih
        $(document).on('change', '#file-input', function () {
            const file = this.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('file', file);
            formData.append('receiver_id', $('#receiver_id').val());

            $.ajax({
                url: "{{ route('chat.upload') }}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function () {
                    loadMessages();

                    const chatBox = $('#chat-box');
                    setTimeout(() => {
                        chatBox.scrollTop(chatBox[0].scrollHeight);
                    }, 100);
                },
                error: function () {
                    alert('Gagal mengirim file.');
                }
            });
        });
    });

    // Event: Klik kontak user
    $(document).on('click', '.user-item', function () {
        selectedUserId = $(this).data('id');
        const contactName = $(this).data('name');
        const contactPhoto = $(this).data('photo');
        isFirstLoad = true;

        if (refreshInterval) clearInterval(refreshInterval);
        refreshInterval = setInterval(function () {
            if (selectedUserId) loadMessages();
        }, 1000);

        $('#receiver_id').val(selectedUserId);
        $('#chat-area').html(`
            <div id="contact-name" class="py-2 px-3 text-white fw-bold rounded-3 d-flex align-items-center border"
                style="position: sticky; top: 0; z-index: 10; background-color: #1d1d1d;">
                <img src="${contactPhoto}" width="32" height="32" class="me-2 rounded-circle" style="object-fit: cover;">
                ${contactName}
            </div>

            <div class="flex-grow-1 overflow-auto" id="chat-box" style="padding: 10px; min-height: 0;"></div>

            <form id="chat-form" class="d-flex mt-1 align-items-center p-2">
                <button type="button" id="upload-btn" class="btn btn-dark p-3 mt-3 me-2" style="border-radius: 20%;" title="Upload file">
                    <i class="fa fa-paperclip text-white"></i>
                </button>
                <input type="file" id="file-input" class="d-none">
                <input type="hidden" id="receiver_id" value="${selectedUserId}">
                <input type="text" id="message" class="form-control me-2 text-white" style="background-color: #1d1d1d;" placeholder="Tulis pesan..."
                    style="border-radius: 1rem;">
                <button type="submit" class="btn btn-primary mt-3" style="border-radius: 1rem;">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        `);
        
        $(document).off('submit', '#chat-form').on('submit', '#chat-form', function (e) {
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

                // Scroll paksa ke bawah setelah kirim
                const chatBox = $('#chat-box');
                setTimeout(() => {
                    chatBox.scrollTop(chatBox[0].scrollHeight);
                }, 100); // delay sedikit supaya pesan baru sempat dirender
            });
        });
    });

    // Fungsi: Load pesan via AJAX GET
    let isFirstLoad = true;

    function loadMessages() {
        if (!selectedUserId) return;

        $.get('{{ url("chat/messages") }}/' + selectedUserId, function (data) {
            const chatBox = $('#chat-box');
            const previousScroll = chatBox.scrollTop();
            const isScrolledToBottom = Math.abs(chatBox[0].scrollHeight - chatBox[0].clientHeight - previousScroll) < 5;

            chatBox.html(''); // Bersihkan dulu

            data.forEach(function (msg) {
                const align = msg.sender_id === currentUserId ? 'text-end' : 'text-start';
                const bgColor = msg.sender_id === currentUserId ? '#14532d' : '#1d1d1d';

                let content = '';
                if (msg.is_file) {
                    const isImage = /\.(jpg|jpeg|png|gif|bmp)$/i.test(msg.file_name);
                    if (isImage) {
                        content = `<img src="${msg.file_url}" class="img-fluid rounded" style="max-width: 200px;">`;
                    } else {
                        content = `<a href="${msg.file_url}" class="text-white" target="_blank" download><i class="fa fa-paperclip text-dark pe-2 text-white"></i> ${msg.file_name}</a>`;
                    }
                } else {
                    content = msg.message;
                }

                const sentAt = new Date(msg.created_at);
                const formattedTime = sentAt.toLocaleString('id-ID', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });

                const bubble = `<div class="${align} mb-2">
                    <span class="px-3 py-2 d-inline-block rounded-3 text-white"
                    style="max-width: 75%; white-space: pre-wrap; word-break: break-word; background-color: ${bgColor};">${content}<br><span class="text-light small" style="font-size: 0.60rem;">${formattedTime}</span></span>
                </div>`;

                chatBox.append(bubble);
            });

            // Scroll ke bawah hanya saat pertama kali buka chat
            if (isFirstLoad) {
                setTimeout(() => {
                    chatBox.scrollTop(chatBox[0].scrollHeight);
                    isFirstLoad = false;
                }, 0);
            } else {
                // Kalau sebelumnya user di bawah, scroll ke bawah (biar realtime), kalau tidak, biarkan posisinya
                if (isScrolledToBottom) {
                    chatBox.scrollTop(chatBox[0].scrollHeight);
                } else {
                    chatBox.scrollTop(previousScroll);
                }
            }
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

    setInterval(function () {
        $.get('{{ route("chat.users.list") }}', function (html) {
            $('#user-list').html(html);
        });
    }, 1000);
</script>

@endsection