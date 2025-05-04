<?php

namespace Database\Factories;
use App\Models\User;
use App\Models\Item;
use App\Models\Rating;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rating>
 */
class RatingFactory extends Factory
{
    protected $model = Rating::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'score' => $this->faker->randomFloat(1, 1, 5), // الدرجة بين 1 و 5
            'comment' => $this->faker->optional()->text(), // تعليق اختياري
            'review_date' => now(), // تاريخ ووقت التقييم الحالي
            'item_id' => Item::inRandomOrder()->first()->id, // اختيار عنصر عشوائي
            'user_id' => User::inRandomOrder()->first()->id, // اختيار مستخدم عشوائي
        ];

        
    }
}
