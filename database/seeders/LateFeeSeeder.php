<?php

namespace Database\Seeders;
use App\Models\LateFee;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LateFeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LateFee::factory(10)->create();

    }
}
