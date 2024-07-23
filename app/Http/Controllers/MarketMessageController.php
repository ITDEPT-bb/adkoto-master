<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketMessageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $messages = Message::with('sender', 'receiver')
            ->where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        return Inertia::render('Messages/Index', [
            'messages' => $messages
        ]);
    }

    public function create($receiverId)
    {
        $receiver = User::findOrFail($receiverId);
        return Inertia::render('Messages/Create', [
            'receiver' => $receiver
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string',
        ]);

        $message = new Message($request->all());
        $message->sender_id = Auth::id();
        $message->save();

        return redirect()->route('messages.index');
    }
}
