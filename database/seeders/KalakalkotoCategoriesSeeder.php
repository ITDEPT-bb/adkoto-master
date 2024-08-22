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
            ['name' => 'Automotive & Vehicles'],
            ['name' => 'Beauty & Cosmetics'],
            ['name' => 'Fashion & Accessories'],
            ['name' => 'Food & Beverage'],
            ['name' => 'Health & Beauty'],
            ['name' => 'Home & Garden'],
            ['name' => 'Technology'],
            ['name' => 'Travel & Hospitality'],
            ['name' => 'Education'],
            ['name' => 'Financial Services'],
            ['name' => 'Real Estate'],
            ['name' => 'Retail'],
            ['name' => 'Telecommunications'],
            ['name' => 'Pets & Animals'],
            ['name' => 'Gaming'],
            ['name' => 'Electronics'],
            ['name' => 'Sports & Fitness'],
            ['name' => 'Books & Stationery'],
            ['name' => 'Toys & Games'],
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
