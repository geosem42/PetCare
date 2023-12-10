<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Pet;
use App\Models\Species;
use App\Models\Breed;
use App\Models\Client;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pet>
 */
class PetFactory extends Factory
{
    protected $model = Pet::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $species_id = Species::all()->random()->id;

        return [
            'name' => $this->faker->name,
            'species_id' => $species_id,
            'breed_id' => Breed::where('species_id', $species_id)->get()->random()->id,
            'client_id' => Client::all()->random()->id,
            'gender' => $this->faker->randomElement(['male', 'female']),
            'age' => $this->faker->numberBetween(1, 30),
        ];
    }
}
