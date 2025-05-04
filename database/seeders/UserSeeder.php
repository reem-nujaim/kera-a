<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       
   $users = [
            [
                'first_name' => 'Eman',
                'last_name' => 'Ahmed',
                'email' => 'Eman1@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'phone' => '773232333',
                'address' => 'User Address',
                'is_active' => true,
                'is_admin' => false,
                'terms_accepted' => true,
                'identity_card_image_front' => 'identity_cards/2025.jpg', // المسار النسبي
                'identity_card_image_back' => 'identity_cards/ff.jpg', // المسار النسبي
                'user_image' => 'identity_cards/10128de459626e6882fc12a0f74c7a5d.jpg', // المسار النسبي
                'identity_card_number'=>50023548148,
            ],
            // [
            //     'first_name' => 'Ali',
            //     'last_name' => 'Yousef',
            //     'email' => 'ali@gmail.com',
            //     'email_verified_at' => now(),
            //     'password' => Hash::make('password123'),
            //     'phone' => '777896589',
            //     'address' => 'User Address',
            //     'is_active' => true,
            //     'is_admin' => false,
            //     'terms_accepted' => true,
            //     'identity_card_image_front' => 'identity_cards/ff.jpg', // المسار النسبي
            //     'user_image' => 'identity_cards/2025.jpg', // المسار النسبي
            //     'identity_card_number'=>50023555158,

            // ],
            // [
            //     'first_name' => 'Sara',
            //     'last_name' => 'Mohammed',
            //     'email' => 'sara@gmail.com',
            //     'email_verified_at' => now(),
            //     'password' => Hash::make('password123'),
            //     'phone' => '771555555',
            //     'address' => 'User Address',
            //     'is_active' => true,
            //     'is_admin' => false,
            //     'terms_accepted' => true,
            //     'identity_card_image_front' => 'identity_cards/10128de459626e6882fc12a0f74c7a5d.jpg', // المسار النسبي
            //     'user_image' => 'identity_cards/10128de459626e6882fc12a0f74c7a5d.jpg', // المسار النسبي
            //     'identity_card_number'=>50044448158,

            // ],
        ];
        
        foreach ($users as $userData) {
            // تحقق من وجود البريد الإلكتروني
            if (User::where('email', $userData['email'])->exists()) {
                continue; // تخطي هذا المستخدم إذا كان البريد الإلكتروني موجودًا بالفعل
            }
        
            // إنشاء المستخدم
            $user = User::create([
                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'email' => $userData['email'],
                'email_verified_at' => $userData['email_verified_at'],
                'password' => $userData['password'],
                'phone' => $userData['phone'],
                'address' => $userData['address'],
                'is_active' => $userData['is_active'],
                'is_admin' => $userData['is_admin'],
                'terms_accepted' => $userData['terms_accepted'],
                'identity_card_image_front' => $userData['identity_card_image_front'],
                'identity_card_image_back' => $userData['identity_card_image_back'],
                'user_image' => $userData['user_image'],
                'identity_card_number' => $userData['identity_card_number'],
            ]);
        
            // تعيين الدور "user" لكل مستخدم تم إنشاؤه
            $user->assignRole('user');
        }
        
        
        

    }
}
