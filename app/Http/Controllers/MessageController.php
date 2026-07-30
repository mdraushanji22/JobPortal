<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        $conversations = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with('sender', 'receiver')
            ->get()
            ->groupBy(function ($message) use ($userId) {
                return $message->sender_id === $userId ? $message->receiver_id : $message->sender_id;
            });

        $users = User::whereIn('id', array_keys($conversations->toArray()))->get();

        return view('messages.index', compact('conversations', 'users'));
    }

    public function conversation(User $user)
    {
        $userId = Auth::id();
        
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        $messages = Message::where(function ($q) use ($userId, $user) {
            $q->where('sender_id', $userId)->where('receiver_id', $user->id);
        })->orWhere(function ($q) use ($userId, $user) {
            $q->where('sender_id', $user->id)->where('receiver_id', $userId);
        })->with('sender', 'receiver')->orderBy('created_at')->get();

        return response()->json($messages);
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required_without:file|nullable|string',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240',
        ]);

        $messageData = [
            'sender_id' => Auth::id(),
            'receiver_id' => $validated['receiver_id'],
            'message' => $validated['message'] ?? null,
        ];

        if ($request->hasFile('file')) {
            $messageData['file_path'] = $request->file('file')->store('chat-files', 'public');
            $messageData['file_type'] = $request->file('file')->getClientOriginalExtension();
        }

        $message = Message::create($messageData);

        if ($request->expectsJson()) {
            return response()->json($message->load('sender'));
        }

        return redirect()->back()->with('success', 'Message sent.');
    }
}
