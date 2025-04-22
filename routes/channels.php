<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.home.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('agora-online-channel', function ($user) {
    return $user ? ['id' => $user->id, 'name' => $user->name] : false;
});

Broadcast::channel('calls.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
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


Broadcast::channel('groupchat.groups.{groupId}', function ($user, $groupId) {
    // Log the attempt to connect to the group channel
    Log::info('Attempting to connect to group channel', ['user_id' => $user->id, 'group_id' => $groupId]);

    // Check if the user is part of the group
    $isConnected = \App\Models\GroupChat::where('id', $groupId)
        ->whereHas('participants', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->exists();

    // Log the result of the connection attempt
    if ($isConnected) {
        Log::info('User connected to group channel', ['user_id' => $user->id, 'group_id' => $groupId]);
    } else {
        Log::warning('User failed to connect to group channel', ['user_id' => $user->id, 'group_id' => $groupId]);
    }

    return $isConnected;
});
