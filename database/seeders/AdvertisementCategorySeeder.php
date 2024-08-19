<?php

namespace Database\Seeders;

use App\Models\AdvertisementCategory;
use Illuminate\Database\Seeder;

class AdvertisementCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicle = AdvertisementCategory::create(['name' => 'Vehicles']);
        $gourmet = AdvertisementCategory::create(['name' => 'Gourmet']);
        $mobilePhone = AdvertisementCategory::create(['name' => 'Mobile Phone']);
        $camera = AdvertisementCategory::create(['name' => 'Camera']);
        $healthAndBeauty = AdvertisementCategory::create(['name' => 'Health & Beauty']);
        $musicAndDance = AdvertisementCategory::create(['name' => 'Music & Dance']);
        $clothingAndShoes = AdvertisementCategory::create(['name' => 'Clothing & Shoes']);
        $home = AdvertisementCategory::create(['name' => 'Home']);
        $pets = AdvertisementCategory::create(['name' => 'Pets']);
        $computers = AdvertisementCategory::create(['name' => 'Computers']);
        $jobs = AdvertisementCategory::create(['name' => 'Jobs']);
        $realEstate = AdvertisementCategory::create(['name' => 'Real Estate']);
        $electronics = AdvertisementCategory::create(['name' => 'Electronics']);
        $kidsAndBaby = AdvertisementCategory::create(['name' => 'Kids & Baby']);
        $events = AdvertisementCategory::create(['name' => 'Events']);
        $misc = AdvertisementCategory::create(['name' => 'Misc']);

        $vehicle->subCategories()->createMany([
            ['name' => 'Bus'],
            ['name' => 'Cars'],
            ['name' => 'Jet Ski'],
            ['name' => 'Motorcycles'],
            ['name' => 'Parts and Accessories'],
        ]);

        $gourmet->subCategories()->createMany([
            ['name' => 'Asian Cuisine'],
            ['name' => 'Cafe & Bar'],
            ['name' => 'Fine Dining'],
            ['name' => 'Bakeries'],
            ['name' => 'Specialty Foods'],
            ['name' => 'Organic Foods'],
            ['name' => 'Gourmet Groceries'],
        ]);

        $mobilePhone->subCategories()->createMany([
            ['name' => 'Smartphones'],
            ['name' => 'Tablets'],
            ['name' => 'Accessories'],
            ['name' => 'Chargers & Cables'],
            ['name' => 'Phone Cases'],
            ['name' => 'Screen Protectors'],
            ['name' => 'Wearables'],
        ]);

        $camera->subCategories()->createMany([
            ['name' => 'DSLR Cameras'],
            ['name' => 'Mirrorless Cameras'],
            ['name' => 'Action Cameras'],
            ['name' => 'Camera Accessories'],
            ['name' => 'Lenses'],
            ['name' => 'Tripods & Supports'],
            ['name' => 'Camera Drones'],
        ]);

        $healthAndBeauty->subCategories()->createMany([
            ['name' => 'Skincare'],
            ['name' => 'Haircare'],
            ['name' => 'Makeup'],
            ['name' => 'Fragrances'],
            ['name' => 'Personal Care'],
            ['name' => 'Health Supplements'],
            ['name' => 'Fitness Equipment'],
        ]);

        $musicAndDance->subCategories()->createMany([
            ['name' => 'Musical Instruments'],
            ['name' => 'DJ Equipment'],
            ['name' => 'Dancewear'],
            ['name' => 'Sheet Music'],
            ['name' => 'Recording Equipment'],
            ['name' => 'Music Lessons'],
            ['name' => 'Dance Classes'],
        ]);

        $clothingAndShoes->subCategories()->createMany([
            ['name' => 'Men\'s Clothing'],
            ['name' => 'Women\'s Clothing'],
            ['name' => 'Kids\' Clothing'],
            ['name' => 'Men\'s Shoes'],
            ['name' => 'Women\'s Shoes'],
            ['name' => 'Kids\' Shoes'],
            ['name' => 'Accessories'],
        ]);

        $home->subCategories()->createMany([
            ['name' => 'Furniture'],
            ['name' => 'Home Decor'],
            ['name' => 'Kitchen Appliances'],
            ['name' => 'Bedding'],
            ['name' => 'Lighting'],
            ['name' => 'Outdoor Furniture'],
            ['name' => 'Cleaning Supplies'],
        ]);

        $pets->subCategories()->createMany([
            ['name' => 'Pet Food'],
            ['name' => 'Pet Accessories'],
            ['name' => 'Pet Health'],
            ['name' => 'Grooming Supplies'],
            ['name' => 'Pet Toys'],
            ['name' => 'Pet Training'],
            ['name' => 'Pet Adoption'],
        ]);

        $computers->subCategories()->createMany([
            ['name' => 'Laptops'],
            ['name' => 'Desktops'],
            ['name' => 'Monitors'],
            ['name' => 'Keyboards & Mice'],
            ['name' => 'Printers & Scanners'],
            ['name' => 'Networking Equipment'],
            ['name' => 'Software'],
        ]);

        $jobs->subCategories()->createMany([
            ['name' => 'Full-Time Jobs'],
            ['name' => 'Part-Time Jobs'],
            ['name' => 'Freelance'],
            ['name' => 'Internships'],
            ['name' => 'Remote Jobs'],
            ['name' => 'Contract Jobs'],
            ['name' => 'Temporary Jobs'],
        ]);

        $realEstate->subCategories()->createMany([
            ['name' => 'Residential Properties'],
            ['name' => 'Commercial Properties'],
            ['name' => 'Land & Plots'],
            ['name' => 'Rental Properties'],
            ['name' => 'Real Estate Agents'],
            ['name' => 'Property Management'],
            ['name' => 'Real Estate Development'],
        ]);

        $electronics->subCategories()->createMany([
            ['name' => 'Televisions'],
            ['name' => 'Audio Systems'],
            ['name' => 'Gaming Consoles'],
            ['name' => 'Home Appliances'],
            ['name' => 'Wearable Technology'],
            ['name' => 'Smart Home Devices'],
            ['name' => 'Cameras & Photography'],
        ]);

        $kidsAndBaby->subCategories()->createMany([
            ['name' => 'Baby Gear'],
            ['name' => 'Toys'],
            ['name' => 'Kids\' Clothing'],
            ['name' => 'Nursery Furniture'],
            ['name' => 'Baby Food'],
            ['name' => 'Diapers & Wipes'],
            ['name' => 'Strollers & Car Seats'],
        ]);

        $events->subCategories()->createMany([
            ['name' => 'Weddings'],
            ['name' => 'Corporate Events'],
            ['name' => 'Birthday Parties'],
            ['name' => 'Concerts'],
            ['name' => 'Festivals'],
            ['name' => 'Exhibitions'],
            ['name' => 'Workshops & Classes'],
        ]);

        $misc->subCategories()->createMany([
            ['name' => 'Books'],
            ['name' => 'Hobbies & Crafts'],
            ['name' => 'Collectibles'],
            ['name' => 'Tickets & Vouchers'],
            ['name' => 'Travel & Experiences'],
            ['name' => 'Gifts & Souvenirs'],
            ['name' => 'Miscellaneous'],
        ]);
    }
}
