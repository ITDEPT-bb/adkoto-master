<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\ItemAttachment;
use App\Models\MarketMessage;
use App\Models\Transaction;

class MarketplaceSeeder extends Seeder
{
    public function run(): void
    {
        // Seed Items
        Item::factory(50)->create()->each(function ($item) {
            ItemAttachment::factory(3)->create(['item_id' => $item->id]);
        });

        // Seed Market Messages
        MarketMessage::factory(100)->create();

        // Seed Transactions
        Transaction::factory(50)->create();
    }
}
