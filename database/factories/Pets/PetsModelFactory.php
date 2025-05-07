<?php

namespace Database\Factories\Pets;

use App\Models\Pets\PetsModel;
use App\Models\People\PeopleModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class PetsModelFactory extends Factory
{

    protected $model = PetsModel::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'pet_name' => $this->faker->firstName,
            'pet_specie' => $this->faker->randomElement(['Dog', 'Cat', 'Bird']),
            'pet_breed' => $this->faker->word,
            'pet_age' => $this->faker->numberBetween(1, 15),
            'pet_image' => null,
            'pet_delete' => 0,
            'person_id' => PeopleModel::inRandomOrder()->first()->id ?? PeopleModel::factory(),
        ];
    }
}
