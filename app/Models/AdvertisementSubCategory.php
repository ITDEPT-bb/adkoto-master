<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertisementSubCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function category()
    {
        return $this->belongsTo(AdvertisementCategory::class, 'category_id');
    }
}
