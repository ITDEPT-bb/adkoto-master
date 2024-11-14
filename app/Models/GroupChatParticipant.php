<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupChatParticipant extends Model
{
    use HasFactory;

    protected $fillable = ['group_chat_id', 'user_id', 'is_admin'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function groupChat()
    {
        return $this->belongsTo(GroupChat::class);
    }
}
