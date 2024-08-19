<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AdvertisementAttachmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Get existing advertisement IDs
        $advertisementIds = DB::table('advertisements')->pluck('id');

        foreach ($advertisementIds as $adId) {
            // Create a random number of attachments for each advertisement
            foreach (range(1, rand(1, 3)) as $index) {
                DB::table('advertisement_attachments')->insert([
                    'advertisement_id' => $adId,
                    'image_path' => 'images/' . $faker->image('public/storage/images', 640, 480, null, false),
                    'created_at' => now(),
                    'updated_at' => now(),
                    'deleted_at' => null,
                ]);
            }
        }
    }
}
