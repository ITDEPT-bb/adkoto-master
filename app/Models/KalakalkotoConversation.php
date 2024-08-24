<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KalakalkotoConversation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kalakal_id',
        'buyer_id',
        'seller_id',
    ];

    public function participants()
    {
        return $this->belongsToMany(User::class, 'conversation_participants', 'conversation_id', 'user_id')
            ->withTimestamps();
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function messages()
    {
        return $this->hasMany(KalakalkotoMessage::class, 'conversation_id');
    }

    public function item()
    {
        return $this->belongsTo(KalakalkotoItem::class, 'kalakal_id');
    }

    // public function lastMessage()
    // {
    //     return $this->belongsTo(Message::class, 'last_message_id');
    // }
}
