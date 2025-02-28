<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('agora-online-channel', function ($user) {
    return $user ? ['id' => $user->id, 'name' => $user->name] : false;
});

Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    // Check if the user is part of the conversation
    return $user->conversations->contains($conversationId);
});

Broadcast::channel('chat.conversations.{conversationId}', function ($user, $conversationId) {
    // Log::info('Authenticating user for chat conversation channel', ['user_id' => $user->id, 'conversation_id' => $conversationId]);
    // Check if the user is part of the conversation
    return \App\Models\Conversation::where('id', $conversationId)
        ->where(function ($query) use ($user) {
            $query->where('user_id1', $user->id)
                ->orWhere('user_id2', $user->id);
        })->exists();
});
