<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Faker\Factory as Faker;
use Carbon\Carbon;

class KalakalkotoItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Sample data for 20 items
        $items = [
            ['user_id' => 1, 'category_id' => 1, 'name' => 'iPhone 13', 'description' => 'Brand new iPhone 13 for sale.', 'price' => 999.99, 'status' => 'available', 'location' => 'New York, NY'],
            ['user_id' => 2, 'category_id' => 2, 'name' => 'Designer Handbag', 'description' => 'Luxury handbag in great condition.', 'price' => 200.00, 'status' => 'available', 'location' => 'Los Angeles, CA'],
            ['user_id' => 3, 'category_id' => 3, 'name' => 'Dining Table Set', 'description' => '6-seater wooden dining table set.', 'price' => 450.00, 'status' => 'sold', 'location' => 'Chicago, IL'],
            ['user_id' => 4, 'category_id' => 4, 'name' => 'Treadmill', 'description' => 'Home treadmill, lightly used.', 'price' => 600.00, 'status' => 'available', 'location' => 'Dallas, TX'],
            ['user_id' => 5, 'category_id' => 5, 'name' => 'Toyota Corolla 2015', 'description' => 'Used Toyota Corolla, excellent condition.', 'price' => 8500.00, 'status' => 'available', 'location' => 'Miami, FL'],
            ['user_id' => 6, 'category_id' => 6, 'name' => 'Harry Potter Book Set', 'description' => 'Complete Harry Potter book set.', 'price' => 120.00, 'status' => 'sold', 'location' => 'San Francisco, CA'],
            ['user_id' => 7, 'category_id' => 7, 'name' => 'Makeup Kit', 'description' => 'Professional makeup kit for sale.', 'price' => 80.00, 'status' => 'available', 'location' => 'Houston, TX'],
            ['user_id' => 8, 'category_id' => 8, 'name' => 'Lego Set', 'description' => 'Star Wars Lego set, unopened.', 'price' => 150.00, 'status' => 'available', 'location' => 'Seattle, WA'],
            ['user_id' => 9, 'category_id' => 9, 'name' => '2 Bedroom Apartment', 'description' => 'Spacious 2-bedroom apartment available for rent.', 'price' => 1200.00, 'status' => 'available', 'location' => 'Las Vegas, NV'],
            ['user_id' => 10, 'category_id' => 10, 'name' => 'Lawn Care Services', 'description' => 'Professional lawn care services in your area.', 'price' => 100.00, 'status' => 'available', 'location' => 'Orlando, FL'],
            ['user_id' => 1, 'category_id' => 1, 'name' => 'Samsung TV', 'description' => '50-inch Samsung LED TV, barely used.', 'price' => 400.00, 'status' => 'available', 'location' => 'New York, NY'],
            ['user_id' => 2, 'category_id' => 2, 'name' => 'Leather Jacket', 'description' => 'Black leather jacket, size M.', 'price' => 150.00, 'status' => 'available', 'location' => 'Los Angeles, CA'],
            ['user_id' => 3, 'category_id' => 3, 'name' => 'Sofa Set', 'description' => '3-piece sofa set in great condition.', 'price' => 700.00, 'status' => 'sold', 'location' => 'Chicago, IL'],
            ['user_id' => 4, 'category_id' => 4, 'name' => 'Mountain Bike', 'description' => '21-speed mountain bike, barely used.', 'price' => 300.00, 'status' => 'available', 'location' => 'Dallas, TX'],
            ['user_id' => 5, 'category_id' => 5, 'name' => 'Honda Civic 2017', 'description' => 'Used Honda Civic in excellent condition.', 'price' => 10000.00, 'status' => 'available', 'location' => 'Miami, FL'],
            ['user_id' => 6, 'category_id' => 6, 'name' => 'Science Fiction Book Collection', 'description' => 'Collection of classic sci-fi novels.', 'price' => 80.00, 'status' => 'sold', 'location' => 'San Francisco, CA'],
            ['user_id' => 7, 'category_id' => 7, 'name' => 'Skincare Set', 'description' => 'Complete skincare set, brand new.', 'price' => 50.00, 'status' => 'available', 'location' => 'Houston, TX'],
            ['user_id' => 8, 'category_id' => 8, 'name' => 'Board Game Collection', 'description' => 'Collection of popular board games.', 'price' => 200.00, 'status' => 'available', 'location' => 'Seattle, WA'],
            ['user_id' => 9, 'category_id' => 9, 'name' => 'Commercial Property for Rent', 'description' => 'Prime commercial property available for lease.', 'price' => 5000.00, 'status' => 'available', 'location' => 'Las Vegas, NV'],
            ['user_id' => 10, 'category_id' => 10, 'name' => 'House Cleaning Services', 'description' => 'Affordable and reliable house cleaning services.', 'price' => 75.00, 'status' => 'available', 'location' => 'Orlando, FL'],
        ];

        // Inserting sample items and their attachments
        foreach ($items as $item) {
            // Insert the item and get the inserted ID
            $itemId = DB::table('kalakalkoto_items')->insertGetId([
                'user_id' => $item['user_id'],
                'category_id' => $item['category_id'],
                'name' => $item['name'],
                'description' => $item['description'],
                'price' => $item['price'],
                'status' => $item['status'],
                'location' => $item['location'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // Use Faker to generate 2 images and store them in kalakalkoto_images folder
            for ($i = 1; $i <= 2; $i++) {
                $imagePath = $faker->image('public/storage/kalakalkoto_images', 640, 480, null, false); // False keeps only the filename

                // Insert into the kalakalkoto_item_attachments table
                DB::table('kalakalkoto_item_attachments')->insert([
                    'kalakal_id' => $itemId,
                    'image_path' => 'kalakalkoto_images/' . $imagePath,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
