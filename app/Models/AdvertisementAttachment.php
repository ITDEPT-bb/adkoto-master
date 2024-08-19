<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdvertisementAttachment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['advertisement_id', 'image_path'];

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class);
    }
}
