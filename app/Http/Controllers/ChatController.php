<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $path = $request->file('file')->store('chat_files', 'public');

        Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'message' => $path, // kita simpan path sebagai "message"
            'file_name' => $request->file('file')->getClientOriginalName(),
            'is_file' => true,
        ]);

        return response()->json(['success' => true]);
    }

    public function refreshUserList()
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

        return view('components.card.user-list-card', compact('users', 'currentUserId'));
    }

    public function getUnreadCount()
    {
        $userId = auth()->id();

        $unreadCount = \App\Models\Message::where('receiver_id', $userId)
            ->where('is_read', false)
            ->count();

        return response()->json(['unread' => $unreadCount]);
    }

    public function deleteMessages(User $user)
    {
        $currentUserId = auth()->id();

        Message::where(function ($q) use ($currentUserId, $user) {
            $q->where('sender_id', $currentUserId)
            ->where('receiver_id', $user->id);
        })->orWhere(function ($q) use ($currentUserId, $user) {
            $q->where('sender_id', $user->id)
            ->where('receiver_id', $currentUserId);
        })->delete();

        return response()->json(['success' => true]);
    }

}
