<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class AdvertisementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Get existing category and subcategory IDs
        $categoryIds = \App\Models\AdvertisementCategory::pluck('id');
        $subCategoryIds = \App\Models\AdvertisementSubCategory::pluck('id');
        $userIds = \App\Models\User::pluck('id');

        foreach (range(1, 10) as $index) {
            Advertisement::create([
                'user_id' => $faker->randomElement($userIds),
                'category_id' => $faker->randomElement($categoryIds),
                'sub_category_id' => $faker->randomElement($subCategoryIds),
                'title' => $faker->word . ' ' . $index,
                'description' => $faker->sentence,
                'price' => $faker->randomFloat(2, 1, 1000),
                'location' => $faker->city,
            ]);
        }
    }
}
