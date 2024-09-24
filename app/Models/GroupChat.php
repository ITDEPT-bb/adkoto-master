<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupChat extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'photo',
        'group_id',
        'owner_id',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'group_chat_participants', 'group_chat_id', 'user_id')
            ->withTimestamps();
    }

    public function group()
    {
        return $this->belongsTo(GroupChat::class, 'group_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'group_id');
    }

    public function lastMessage()
    {
        return $this->belongsTo(Message::class, 'last_message_id');
    }

    public function conversation()
    {
        return $this->hasOne(Conversation::class, 'group_id');
    }
}
