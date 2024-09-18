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

        // Sample data for auction items
        $auctionItems = [];

        for ($i = 1; $i <= 20; $i++) {
            $auctionItems[] = [
                'user_id' => rand(1, 10),
                'category_id' => rand(1, 5),
                'name' => $faker->word . ' ' . $faker->word,
                'description' => $faker->sentence,
                'location' => $faker->city,
                'starting_price' => $faker->randomFloat(2, 100, 5000),
                'bid_increment' => $faker->randomFloat(2, 1, 100),
                'bidding_type' => $faker->randomElement(['live', 'normal']),
                'auction_ends_at' => Carbon::now()->addDays(rand(1, 14)),
            ];
        }

        // Insert sample auction items
        foreach ($auctionItems as $item) {
            $itemId = DB::table('auction_items')->insertGetId([
                'user_id' => rand(1, 10),
                'category_id' => rand(1, 5),
                'name' => $faker->word . ' ' . $faker->word,
                'description' => $faker->sentence,
                'location' => $faker->city,
                'starting_price' => $faker->randomFloat(2, 100, 5000),
                'bid_increment' => $faker->randomFloat(2, 1, 100),
                'bidding_type' => $faker->randomElement(['live', 'normal']),
                'auction_ends_at' => Carbon::now()->addDays(rand(1, 14)),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // Generate attachments for the auction items
            for ($j = 1; $j <= 2; $j++) {
                $imagePath = $faker->image('public/storage/auction_images', 640, 480, null, false);

                DB::table('auction_item_attachments')->insert([
                    'auction_id' => $itemId,
                    'image_path' => 'auction_images/' . $imagePath,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            // Generate bids for the auction items
            for ($k = 1; $k <= 5; $k++) {
                DB::table('bids')->insert([
                    'user_id' => $faker->numberBetween(1, 10),
                    'auction_item_id' => $itemId,
                    'bid_amount' => $item['starting_price'] + ($k * $item['bid_increment']),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
