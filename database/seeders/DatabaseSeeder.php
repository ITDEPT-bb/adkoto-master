<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // $this->call(MarketplaceSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(WalletSeeder::class);
        $this->call(KalakalkotoCategoriesSeeder::class);
        $this->call(KalakalkotoItemsSeeder::class);
        $this->call(AdvertisementCategorySeeder::class);
        $this->call(AdvertisementSeeder::class);
    }
}
