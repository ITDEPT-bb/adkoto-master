<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KalakalkotoCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics'],
            ['name' => 'Fashion & Accessories'],
            ['name' => 'Home & Furniture'],
            ['name' => 'Sports & Fitness'],
            ['name' => 'Automotive & Vehicles'],
            ['name' => 'Books & Stationery'],
            ['name' => 'Health & Beauty'],
            ['name' => 'Toys & Games'],
            ['name' => 'Real Estate'],
            ['name' => 'Services'],
        ];

        foreach ($categories as $category) {
            DB::table('kalakalkoto_categories')->insert([
                'name' => $category['name'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
