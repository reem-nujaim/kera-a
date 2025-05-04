<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;
    

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // كلمة مرور مشفرة
            'phone' => $this->faker->unique()->phoneNumber(),
            'address' => $this->faker->address(),
            'user_image' => $this->faker->imageUrl(300, 300, 'people'), // صورة عشوائية
            'identity_card_image_front' => $this->faker->imageUrl(300, 200, 'id card'), // صورة بطاقة
            'identity_card_image_back' => $this->faker->imageUrl(300, 200, 'id card back'), 
            'identity_card_number' => $this->faker->unique()->numerify('############'), // رقم مكون من 12 رقماً
            'is_active' => $this->faker->boolean(80), // 80% أن تكون نشطة
            'terms_accepted' => $this->faker->boolean(100), // دائما مقبولة
            'remember_token' => Str::random(10),
            'is_admin' => $this->faker->boolean(20), // 20% أن يكون المستخدم مديراً (أو يمكنك ضبطها بشكل ثابت).
        ];

//--------------------------------------------------------------------------------------------------------------------------//


        
    }


    
    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
