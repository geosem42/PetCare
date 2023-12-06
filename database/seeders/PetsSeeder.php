<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pet;
use App\Models\Species;
use App\Models\Breed;
use App\Models\Client;
use Faker\Factory as Faker;

class PetsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $genders = ['male', 'female'];
        $species = Species::all();
        $clients = Client::all();

        for ($i = 0; $i < 55; $i++) {
            $name = $faker->firstName;
            $speciesRandom = $species->random();
            $breedRandom = Breed::where('species_id', $speciesRandom->id)->get()->random();
            $clientRandom = $clients->random();

            Pet::create([
                'client_id' => $clientRandom->id,
                'slug' => strtolower(str_replace(' ', '-', $name)),
                'name' => $name,
                'species_id' => $speciesRandom->id,
                'breed_id' => $breedRandom->id,
                'age' => rand(1, 10),
                'gender' => $genders[array_rand($genders)],
                'photo' => '',
            ]);
        }
    }
}
