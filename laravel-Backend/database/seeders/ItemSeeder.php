<?php

namespace Database\Seeders;
use App\Models\Category;
use App\Models\User;
use App\Models\Item;
use Illuminate\Support\Facades\DB;  // إضافة السطر هنا
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class ItemSeeder extends Seeder
{
    public function run()
    {
        Item::create([
            'name' => 'كاميرا احترافية',
            'description' => 'كاميرا بجودة تصوير عالية مناسبة للمصورين المحترفين.',
            'price_per_day' => 100,
            'images' => json_encode([
                'storage/identity_cards/rr.png',
                'storage/identity_cards/ss.png',
                'storage/identity_cards/mm.png',
            ]),

            'status' => 'excellent',
            'available' => true,
            'admin_approval' => true,
            'location' => 'الرياض',
            'GPS_location' => DB::raw("ST_GeomFromText('POINT(24.7136 46.6753)')"),
            'price_assurance' => 200.00,
            'delivery_method' => 'self',
            'price_per_hour' => 15.00,
            'quantity' => 2,
            'min_rental_duration' => 1,
            'max_rental_duration' => 30,
            'availability_hours' => 12,
            'user_id' => 2,
            'category_id' => 1,
        ]);

    }
}
