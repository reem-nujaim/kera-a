<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB; // إضافة هذا السطر
use App\Models\RentalRequest;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class Rental_RequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('rental_requests')->insert([
            [
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(5),
                'request_date' => Carbon::now(),
                'status' => 'pending',
                'admin_reviewed' => false,
                'amount' => 100.00,
                'payment_method' => 'cash',
                'payment_status' => 'pending',
                'delivery_method' => 'self',
                'transaction_number'=>null,
                'user_id' => 2,  // Assuming a user with ID 1 exists
                'item_id' => 47,  // Assuming an item with ID 1 exists
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'start_date' => Carbon::now()->addDays(1),
                'end_date' => Carbon::now()->addDays(3),
                'request_date' => Carbon::now(),
                'status' => 'approved',
                'admin_reviewed' => true,
                'amount' => 50.00,
                'payment_method' => 'bank_transfer',
                'transaction_number'=>4512500584,
                'payment_status' => 'completed',
                'delivery_method' => 'courier',
                'user_id' => 2,  // Assuming a user with ID 2 exists
                'item_id' => 47,  // Assuming an item with ID 2 exists
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
