<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Resources\UserResource;
use App\Models\Block;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ConversationController extends Controller
{
    public function index(User $user)
    {
        $isBlockedByAuthUser = Block::query()
            ->where('user_id', auth()->id()) // Authenticated user blocked the other user
            ->where('blocked_user_id', $user->id)
            ->exists();

        $isBlockedByOtherUser = Block::query()
            ->where('user_id', $user->id) // Other user blocked the authenticated user
            ->where('blocked_user_id', auth()->id())
            ->exists();

        $conversation = Conversation::where(function ($query) use ($user) {
            $query->where('user_id1', auth()->id())
                ->where('user_id2', $user->id);
        })->orWhere(function ($query) use ($user) {
            $query->where('user_id1', $user->id)
                ->where('user_id2', auth()->id());
        })->with('messages.attachments')->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_id1' => auth()->id(),
                'user_id2' => $user->id,
            ]);
        }

        $messages = $conversation->messages;

        return Inertia::render('Conversation/Index', [
            'conversation' => $conversation,
            'messages' => $messages,
            'user' => new UserResource($user),
            'isBlockedByAuthUser' => $isBlockedByAuthUser,
            'isBlockedByOtherUser' => $isBlockedByOtherUser,
        ]);
    }

    public function storeMessage(Request $request, Conversation $conversation)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id'       => $request->user()->id,
            'message'         => $request->input('message'),
            'attachments'     => json_encode([]),
            'created_at'      => now(),
        ]);

        // Broadcast the new message event
        event(new MessageSent($message));

        return response()->json($message);
    }
}
