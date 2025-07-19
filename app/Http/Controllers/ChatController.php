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

        $users = User::where('id', '!=', $currentUserId)->get();

        $users->map(function ($user) use ($currentUserId) {
            $user->lastMessage = $user->lastMessageWith($currentUserId);
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
}
