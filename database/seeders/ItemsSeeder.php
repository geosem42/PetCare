<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = ['Stethoscope', 'Surgical Gloves', 'Bandages', 'Antiseptic Spray', 'Syringes', 'Needles', 'Pet Shampoo', 'Flea Treatment', 'Worming Tablets', 'Vaccines', 'Microchip Scanner', 'Ultrasound Machine', 'X-ray Machine', 'Surgical Instruments', 'Pet Food', 'Pet Vitamins', 'Pet Medications', 'Pet Toys', 'Pet Beds', 'Pet Carriers', 'Pet Leashes', 'Pet Collars', 'Pet Bowls', 'Pet Treats', 'Pet Litter', 'Pet Grooming Tools', 'Pet Dental Care Products', 'Pet Eye Care Products', 'Pet Ear Care Products', 'Pet Skin Care Products', 'Pet Hair Care Products', 'Pet Nail Care Products', 'Pet First Aid Supplies', 'Pet Training Supplies', 'Pet Cleaning Supplies', 'Pet Odor Control Products', 'Pet Waste Disposal Products', 'Pet Apparel', 'Pet Furniture', 'Pet Doors', 'Pet Gates', 'Pet Ramps', 'Pet Steps', 'Pet Strollers', 'Pet Car Seats', 'Pet Seat Covers', 'Pet Harnesses', 'Pet Muzzles', 'Pet ID Tags', 'Pet Flea Combs', 'Pet Tick Removers', 'Pet Heartworm Medications', 'Pet Dewormers', 'Pet Joint Health Supplements', 'Pet Probiotics', 'Pet Antioxidants', 'Pet Digestive Remedies', 'Pet Relaxants', 'Pet Recovery Collars'];

        for ($i = 0; $i < 56; $i++) {
            DB::table('items')->insert([
                'item_name' => $items[$i],
                'slug' => Str::slug($items[$i]),
                'description' => $items[$i] . ' description',
                'quantity' => rand(1, 100),
                'unit_price' => rand(1, 100),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
