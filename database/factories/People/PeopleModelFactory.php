<?php

namespace Database\Factories\People;

use Illuminate\Support\Str;
use App\Models\People\PeopleModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class PeopleModelFactory extends Factory
{
    protected $model = PeopleModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'person_name' => $this->faker->firstName,
            'person_lastname' => $this->faker->lastName,
            'person_email' => $this->faker->unique()->safeEmail,
            'person_phone' => $this->faker->numerify('3#########'),
            'person_birthdate' => $this->faker->date('Y-m-d'),
            'person_delete' => 0,
        ];
    }
}
