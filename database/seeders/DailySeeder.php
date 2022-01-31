<?php

namespace Database\Seeders;

use App\Models\Daily;
use Illuminate\Database\Seeder;

class DailySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Daily::insert([
            [
                'user_id' => 1,
                'task' => 'monitoring server',
                'date' => now(),
                'time' => '09:00 AM',
                'status' => '0',
            ],
        ]);
    }
}
