<?php

namespace Database\Seeders;
use App\Models\Assurance;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssuranceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Assurance::factory()->count(10)->create(); // إنشاء 10 سجلات من التأمين
    }
}
