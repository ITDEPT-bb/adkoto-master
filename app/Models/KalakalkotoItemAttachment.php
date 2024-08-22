<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KalakalkotoItemAttachment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['kalakal_id', 'image_path'];

    public function kalakalkotoItem()
    {
        return $this->belongsTo(KalakalkotoItem::class, 'kalakal_id');
    }
}
