<?php

namespace Database\Seeders;

use App\Models\FuelLog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FuelLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FuelLog::factory()->count(10)->create();
    }
}
