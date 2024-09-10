<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\AuctionItem;
use Illuminate\Support\Facades\Storage;
use Faker\Factory as Faker;
use Carbon\Carbon;

class AuctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Create 20 auction items
        for ($i = 0; $i < 20; $i++) {
            $auctionItem = AuctionItem::create([
                'user_id' => rand(1, 10),
                'category_id' => rand(1, 5),
                'name' => $faker->word . ' ' . $faker->word,
                'description' => $faker->sentence,
                'location' => $faker->city,
                'starting_price' => $faker->randomFloat(2, 100, 5000),
                'current_bid' => $faker->optional()->randomFloat(2, 100, 5000),
                'bid_increment' => $faker->randomFloat(2, 1, 100),
                'bidding_type' => $faker->randomElement(['live', 'normal']),
                'auction_ends_at' => Carbon::now()->addDays(rand(1, 14)),
            ]);

            // for ($j = 0; $j < 2; $j++) {
            //     $imagePath = $faker->image('public/storage/auction_images', 640, 480, null, false);

            //     AuctionItemAttachment::create([
            //         'auction_id' => $auctionItem->id,
            //         'image_path' => 'uploads/auction_images/' . $imagePath,
            //     ]);
            // }

            for ($i = 1; $i <= 2; $i++) {
                $imagePath = $faker->image('public/storage/auction_images', 640, 480, null, false);

                // Insert into the kalakalkoto_item_attachments table
                DB::table('auction_item_attachments')->insert([
                    'auction_id' => $auctionItem->id,
                    'image_path' => 'uploads/auction_images/' . $imagePath,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
