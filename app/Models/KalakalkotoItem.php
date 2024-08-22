<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KalakalkotoItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'category_id', 'name', 'description', 'price', 'status', 'location'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(KalakalkotoCategory::class);
    }

    public function attachments()
    {
        return $this->hasMany(KalakalkotoItemAttachment::class, 'kalakal_id');
    }

    public function conversations()
    {
        return $this->hasMany(KalakalkotoConversation::class);
    }
}
