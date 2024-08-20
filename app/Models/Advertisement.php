<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Advertisement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'sub_category_id',
        'title',
        'description',
        'price',
        'location'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(AdvertisementCategory::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(AdvertisementSubCategory::class);
    }

    public function attachments()
    {
        return $this->hasMany(AdvertisementAttachment::class);
    }
}
