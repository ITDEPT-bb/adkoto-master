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
                // Generate a fake image and store it in the 'advertisement_images' directory
                $imagePath = $faker->image('public/storage/advertisement_images', 640, 480, null, false);

                // Insert attachment record into the database
                DB::table('advertisement_attachments')->insert([
                    'advertisement_id' => $adId,
                    'image_path' => 'advertisement_images/' . $imagePath,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'deleted_at' => null,
                ]);
            }
        }
    }
}
