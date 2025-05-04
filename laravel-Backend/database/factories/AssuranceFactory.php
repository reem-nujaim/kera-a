<?php

namespace Database\Factories;
use App\Models\Item;
use App\Models\RentalRequest;
use App\Models\Assurance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Assurance>
 */
class AssuranceFactory extends Factory
{
    protected $model = Assurance::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'amount' => $this->faker->randomFloat(2, 50, 1000), // مبلغ عشوائي بين 50 و 1000
            'en_description' => $this->faker->sentence(), // وصف عشوائي للتأمين
            'ar_description' => $this->faker->sentence(), // وصف عشوائي للتأمين
            'is_returned' => $this->faker->boolean(20), // نسبة 20% أن يكون التأمين مسترجعًا
            'item_id' => Item::inRandomOrder()->first()->id, // اختيار عنصر عشوائي
            'rental_request_id' => RentalRequest::inRandomOrder()->first()->id, // اختيار طلب تأجير عشوائي
        ];
    }
}
