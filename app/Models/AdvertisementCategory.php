<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertisementCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function advertisements()
    {
        return $this->hasMany(Advertisement::class, 'category_id');
    }

    public function subCategories()
    {
        return $this->hasMany(AdvertisementSubCategory::class, 'category_id');
    }
}
