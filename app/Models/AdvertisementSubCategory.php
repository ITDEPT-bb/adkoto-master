<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertisementSubCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function advertisements()
    {
        return $this->hasMany(Advertisement::class, 'sub_category_id');
    }

    public function category()
    {
        return $this->belongsTo(AdvertisementCategory::class, 'category_id');
    }
}
