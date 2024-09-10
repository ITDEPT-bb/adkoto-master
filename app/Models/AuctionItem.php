<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuctionItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'description',
        'location',
        'starting_price',
        'current_bid',
        'bid_increment',
        'bidding_type',
        'auction_ends_at',
    ];

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

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
        return $this->hasMany(AuctionItemAttachment::class, 'auction_id');
    }
}
