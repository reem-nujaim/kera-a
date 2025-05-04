<?php

namespace Database\Seeders;
use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use App\Models\RentalRequest;
use App\Models\Assurance;
use App\Models\Bill;
use App\Models\Rating;
use App\Models\LateFee;
use App\Models\Setting;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            Rental_RequestSeeder::class,
            AssuranceSeeder::class,
            BillSeeder::class,
            RatingSeeder::class,
            LateFeeSeeder::class,
            SettingSeeder::class,

        ]);



    }
}
