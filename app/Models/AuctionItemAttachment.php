<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuctionItemAttachment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['auction_id', 'image_path'];

    public function AuctionItem()
    {
        return $this->belongsTo(AuctionItem::class, 'auction_id');
    }
}
