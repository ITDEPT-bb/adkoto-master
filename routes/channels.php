<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('agora-online-channel', function ($user) {
    return $user ? ['id' => $user->id, 'name' => $user->name] : false;
});
