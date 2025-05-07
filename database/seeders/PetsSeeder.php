<?php

namespace Database\Seeders;

use App\Models\Pets\PetsModel;
use Illuminate\Database\Seeder;

class PetsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PetsModel::factory()->count(10)->create();
    }
}
