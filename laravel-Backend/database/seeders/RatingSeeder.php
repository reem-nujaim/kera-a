<?php

namespace Database\Seeders;

use App\Models\Rating;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Rating::create([
            'score' => 5,
            'comment' => 'Great product!',
            'review_date' => now(),
            'item_id' => 3,  // تأكد من أن هذا المنتج موجود في جدول items
            'user_id' => 1,  // تأكد من أن هذا المستخدم موجود في جدول users
        ]);

        Rating::create([
            'score' => 3,
            'comment' => 'Good, but could be improved.',
            'review_date' => now(),
            'item_id' => 4,  // تأكد من أن هذا المنتج موجود في جدول items
            'user_id' => 2,  // تأكد من أن هذا المستخدم موجود في جدول users
        ]);
    }
}
