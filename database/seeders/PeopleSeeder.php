<?php

namespace Database\Seeders;

use App\Models\People\PeopleModel;
use Illuminate\Database\Seeder;

class PeopleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        PeopleModel::factory()->count(10)->create();
    }
}
