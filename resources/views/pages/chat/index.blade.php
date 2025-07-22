@extends('layouts.user_type.auth')

@section('title', 'Chat Paskomnas || PISS')

@section('content')
<div class="container-fluid card py-3 mb-3" id="chat-container">
    <div class="row" style="height: 700px;">
        <div class="col-4 border-end overflow-auto h-100">
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

    #message::-webkit-scrollbar {
        display: none;
    }

    .copyable-message {
        user-select: text;
        cursor: text;
    }

    .copyable-message.only-emoji {
        font-size: 3rem;
        text-align: center;
        line-height: 1;
    }

    #emoji-picker {
        max-width: 250px;
        line-height: 2;
        cursor: pointer;
        user-select: none;

        transition: all 0.3s ease;
        opacity: 0;
        transform: translateY(10px);
        pointer-events: none;
        position: absolute;
        z-index: 999;
        background: white;
    }

    #emoji-picker span {
        font-size: 1.3rem;
        margin: 3px;
        display: inline-block;
    }

    #emoji-picker.show {
        opacity: 1;
        transform: translateY(0);
        pointer-events: auto;
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

        // Emoji & Tampilkan/semmbunyikan picker dengan animasi
        $(document).on('click', '#emoji-btn', function () {
            $('#emoji-picker').toggleClass('show');
        });

        // Ketika emoji diklik, langsung kirim
        $(document).on('click', '#emoji-picker span, #emoji-picker div', function () {
            const emoji = $(this).text();
            const receiverId = $('#receiver_id').val();

            $.ajax({
                url: "{{ route('chat.messages') }}",
                method: 'POST',
                data: {
                    message: emoji,
                    receiver_id: receiverId
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    console.log("Berhasil:", res);
                    $('#message').val('');
                    $('#emoji-picker').removeClass('show'); // SEMBUNYIKAN dg animasi
                    loadMessages();
                },
                error: function (xhr) {
                    console.log("Error:", xhr.responseText);
                    alert('Gagal mengirim emoji.');
                }
            });
        });

        // Klik di luar => sembunyikan
        $(document).on('click', function (e) {
            if (!$(e.target).closest('#emoji-btn, #emoji-picker').length) {
                $('#emoji-picker').removeClass('show');
            }
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
            if (selectedUserId && !isSelectingText) {
                loadMessages();
            }
        }, 1000);

        $('#receiver_id').val(selectedUserId);
        $('#chat-area').html(`
            <div id="contact-name" class="py-2 px-3 text-white fw-bold rounded-3 d-flex align-items-center border"
                style="position: sticky; top: 0; z-index: 10; background-color: #1d1d1d;">
                <img src="${contactPhoto}" width="32" height="32" class="me-2 rounded-circle" style="object-fit: cover;">
                ${contactName}

                <i class="fa fa-trash ms-auto text-danger cursor-pointer" data-user-name="${contactName}" onclick="confirmDeleteChat(${selectedUserId}, this)" title="Hapus Chat"></i>
            </div>

            <div class="flex-grow-1 overflow-auto" id="chat-box" style="padding: 10px; min-height: 0;"></div>

            <form id="chat-form" class="d-flex mt-1 align-items-center p-2"
                style="position: relative; z-index: 100;">
                <button type="button" id="upload-btn" class="btn btn-dark p-3 mt-3 me-2" style="border-radius: 20%;" title="Upload file">
                    <i class="fa fa-paperclip text-white"></i>
                </button>
                <input type="file" id="file-input" class="d-none">

                <button type="button" id="emoji-btn" class="btn btn-dark p-3 mt-3 me-2" style="border-radius: 20%;" title="Emoji">
                    😊
                </button>
                <div id="emoji-picker" class="bg-white border rounded p-2"
                    style="position: absolute; bottom: 80px; left: 60px; z-index: 9999;">
                    <span>😀</span> <span>😁</span> <span>😂</span> <span>🤣</span> <span>😊</span>
                    <span>😎</span> <span>😍</span> <span>😢</span> <span>😡</span> <span>👍</span>
                    <span>🙌</span> <span>🙏</span> <span>❤️</span> <span>💔</span> <span>🎉</span> <span>💯</span>
                </div>

                <input type="hidden" id="receiver_id" value="${selectedUserId}">
                <textarea id="message" class="form-control me-2 text-white"
                    style="background-color: #1d1d1d; border-radius: 1rem; resize: none; overflow: hidden;"
                    placeholder="Tulis pesan..."></textarea>

                <button id="send-button" type="submit" class="btn btn-primary mt-3" style="border-radius: 1rem;">
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

            if (data.length === 0) {
                // Tampilkan placeholder jika belum ada chat
                chatBox.html(`
                    <div class="text-center text-muted mt-5">
                        <img src="{{ asset('assets/img/select-contact.png') }}" width="300" class="mb-3">
                        <h5 class="text-white">Belum ada chat</h5>
                        <p class=""text-white>Kirim pesan untuk memulai percakapan</p>
                    </div>
                `);
                return;
            }

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

                const onlyEmoji = isOnlyEmoji(content) ? 'only-emoji' : '';

                const bubble = `<div class="${align} mb-3">
                    <span class="px-3 py-2 d-inline-block rounded-3 text-white copyable-message  ${onlyEmoji}"
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

    // Delete chat
    const deleteChatUrlTemplate = `{{ route('chat.delete', ['user' => '__USER_ID__']) }}`;
    function confirmDeleteChat(selectedUserId, el) {
        const deleteUrl = deleteChatUrlTemplate.replace('__USER_ID__', selectedUserId);
        const selectedUserName = $(el).data('user-name');

        Swal.fire({
            title: 'Hapus semua pesan?',
            html: `Semua pesan dengan <strong>${selectedUserName}</strong> akan dihapus secara permanen.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: deleteUrl,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire('Berhasil', 'Semua pesan telah dihapus.', 'success');
                        $('#chat-box').html('');
                        $('#chat-placeholder').show();
                        $('#contact-name').remove(); // Optional: sembunyikan header
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire('Gagal', 'Terjadi kesalahan saat menghapus pesan.', 'error');
                    }
                });
            }
        });
    }

    // Copy text
    let isSelectingText = false;
    document.addEventListener('selectionchange', () => {
        const selection = window.getSelection();
        isSelectingText = selection && selection.toString().length > 0;
    });

    // Klik di luar → sembunyikan picker
    function isOnlyEmoji(text) {
        const stripped = text.replace(/\s/g, '');
        // Regex emoji dasar + tambahan karakter skin tone, gender, dsb
        const emojiRegex = /^(\p{Emoji_Presentation}|\p{Emoji}\uFE0F)+$/u;
        try {
            return emojiRegex.test(stripped);
        } catch (e) {
            return false; // fallback jika browser tidak support \p{}
        }
    }

    // Input message
    $(document).on('keydown', '#message', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            $('#send-button').click();

            $(this).val('');
            this.style.height = 'auto';
        }
    });

    $(document).on('input', '#message', function () {
        this.style.height = 'auto';
        const maxHeight = 100;
        this.style.overflowY = this.scrollHeight > maxHeight ? 'scroll' : 'hidden';
        this.style.height = Math.min(this.scrollHeight, maxHeight) + 'px';
    });
</script>

@endsection