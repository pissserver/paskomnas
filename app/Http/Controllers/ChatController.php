<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    public function index()
    {
        $currentUserId = auth()->id();

        $users = User::where('id', '!=', $currentUserId)
            ->orderBy('name')
            ->get();

        $users->map(function ($user) use ($currentUserId) {
            $user->lastMessage = $user->lastMessageWith($currentUserId);
            $user->unreadCount = $user->unreadMessagesCount($currentUserId);
            return $user;
        });

        return view('pages.chat.index', compact('users', 'currentUserId'));
    }

    public function fetchMessages(User $user)
    {
        $messages = Message::where(function ($q) use ($user) {
            $q->where('sender_id', auth()->id())->where('receiver_id', $user->id);
        })->orWhere(function ($q) use ($user) {
            $q->where('sender_id', $user->id)->where('receiver_id', auth()->id());
        })->orderBy('created_at')->get();

        // Tandai pesan masuk sebagai dibaca
        Message::where('sender_id', $user->id)
            ->where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Tambahkan field file_url jika is_file = true
        $messages->transform(function ($msg) {
            return [
                'id' => $msg->id,
                'sender_id' => $msg->sender_id,
                'receiver_id' => $msg->receiver_id,
                'message' => $msg->is_file ? null : $msg->message,
                'file_name' => $msg->file_name,
                'is_file' => $msg->is_file,
                'file_url' => $msg->is_file ? asset('storage/' . $msg->message) : null,
                'created_at' => $msg->created_at->toDateTimeString(),
            ];
        });

        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        return response()->json($message);
    }

    public function uploadFile(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10120',
            'receiver_id' => 'required|exists:users,id',
        ]);

        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();

        // Simpan file ke storage/app/public/chat_files/
        $file->storeAs('public/chat_files', $filename);

        // Simpan path relatif (tanpa 'public/') agar cocok dengan disk('public')
        $relativePath = 'chat_files/' . $filename;

        Message::create([
            'sender_id'   => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'message'     => $relativePath, // hanya 'chat_files/xxx.jpg'
            'file_name'   => $file->getClientOriginalName(),
            'is_file'     => true,
        ]);

        return response()->json(['success' => true]);
    }


    public function refreshUserList(Request $request)
    {
        $currentUserId = auth()->id();
        $keyword = $request->input('search');

        $users = User::where('id', '!=', $currentUserId)
            ->when($keyword, function ($query, $keyword) {
                return $query->where('name', 'like', '%' . $keyword . '%');
            })
            ->orderBy('name')
            ->get();

        $users->map(function ($user) use ($currentUserId) {
            $user->lastMessage = $user->lastMessageWith($currentUserId);
            $user->unreadCount = $user->unreadMessagesCount($currentUserId);
            return $user;
        });

        return view('components.card.user-list-card', compact('users', 'currentUserId'));
    }


    public function getUnreadCount()
    {
        $userId = auth()->id();

        $unreadCount = Message::where('receiver_id', $userId)
            ->where('is_read', false)
            ->count();

        return response()->json(['unread' => $unreadCount]);
    }

    public function deleteMessages(User $user)
    {
        $currentUserId = auth()->id();

        // Ambil semua pesan terlebih dahulu
        $messages = Message::where(function ($q) use ($currentUserId, $user) {
            $q->where('sender_id', $currentUserId)
            ->where('receiver_id', $user->id);
        })->orWhere(function ($q) use ($currentUserId, $user) {
            $q->where('sender_id', $user->id)
            ->where('receiver_id', $currentUserId);
        })->get(); // ✅ Ambil data dulu

        // Hapus file jika is_file = true
        foreach ($messages as $message) {
            if ($message->is_file && $message->message) {
                Storage::disk('public')->delete($message->message);
            }
        }

        // Lalu hapus semua pesan dari DB
        Message::whereIn('id', $messages->pluck('id'))->delete();

        return response()->json(['success' => true]);
    }

    public function deleteSenderMessages(Request $request)
    {
        $message = Message::find($request->id);

        if ($message && $message->sender_id === auth()->id()) {
            // Hapus file jika is_file = true
            if ($message->is_file && $message->message) {
                Storage::disk('public')->delete($message->message);
            }

            $message->delete();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 403);
    }

    public function getUnreadMessages()
    {
        $userId = auth()->id();

        $messages = Message::with('sender')
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->latest()
            ->take(5)
            ->get();

        $data = $messages->map(function ($msg) {
            $messageContent = '';

            if ($msg->is_file) {
                $ext = strtolower(pathinfo($msg->file_name, PATHINFO_EXTENSION));
                $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']);

                if ($isImage) {
                    $messageContent = '<i class="fas fa-camera me-1"></i> Foto';
                } else {
                    $messageContent = '<i class="fas fa-paperclip me-1"></i> File';
                }
            } else {
                $messageContent = e(Str::limit($msg->message, 50));
            }

            return [
                'id' => $msg->id,
                'name' => $msg->sender->name,
                'avatar' => $msg->sender->photo ? $msg->sender->photo : asset('assets/img/avatar.png'),
                'message' => $messageContent,
                'time' => $msg->created_at->diffForHumans(),
                'url' => route('chat.fetch', ['user' => $msg->sender_id]),
            ];
        });

        return response()->json(['messages' => $data]);
    }

}
