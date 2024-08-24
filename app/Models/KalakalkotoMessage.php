<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KalakalkotoMessage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'message',
        'sender_id',
        'receiver_id',
        'conversation_id',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function conversation()
    {
        return $this->belongsTo(KalakalkotoConversation::class, 'conversation_id');
    }

    public function attachments()
    {
        return $this->hasMany(KalakalkotoMessageAttachment::class);
    }
}
