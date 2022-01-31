<?php

namespace Database\Seeders;

use App\Models\Jenistodo;
use Illuminate\Database\Seeder;

class JenisToDoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Jenistodo::insert([
            [
                'name' => "Daily"
            ],
            [
                'name' => "Weekly"
            ],
            [
                'name' => "Monthly"
            ],
        ]);
    }
}
