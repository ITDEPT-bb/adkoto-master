<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Block;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KalakalkotoChatController extends Controller
{
    public function getKalakalConversation(User $user)
    {
        $appId = env('AGORA_APP_ID');

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
                ->where('user_id2', $user->id)
                ->where('type', 'kalakalkoto');
        })->orWhere(function ($query) use ($user) {
            $query->where('user_id1', $user->id)
                ->where('user_id2', auth()->id())
                ->where('type', 'kalakalkoto');
        })->with('messages.attachments')->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_id1' => auth()->id(),
                'user_id2' => $user->id,
                'type' => 'kalakalkoto',
            ]);
        }

        $messages = $conversation->messages;

        return Inertia::render('Chat/Conversation', [
            'conversation' => $conversation,
            'messages' => $messages,
            'user' => new UserResource($user),
            'isBlockedByAuthUser' => $isBlockedByAuthUser,
            'isBlockedByOtherUser' => $isBlockedByOtherUser,
            'appId' => $appId,
        ]);
    }
}
