<?php

namespace Database\Factories;

use App\Models\LateFee;
use App\Models\RentalRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LateFee>
 */
class LatefeeFactory extends Factory
{
    protected $model = LateFee::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number_of_late_hours' => $this->faker->time('H:i:s'), // عدد ساعات التأخير بصيغة HH:MM:SS
            'fee_per_late_hour' => $this->faker->randomFloat(2, 1, 50), // الرسوم لكل ساعة تأخير بين 1 و 50
            'total_fee' => $this->faker->randomFloat(2, 10, 500), // إجمالي الرسوم بين 10 و 500
            'late_fee_date' => now(), // تاريخ فرض الرسوم مع الوقت
            'paid' => $this->faker->boolean(), // قيمة عشوائية لعمود "تم الدفع" (اختياري بين true و false)
            'rental_request_id' => RentalRequest::inRandomOrder()->first()->id, // اختيار طلب تأجير عشوائي
        ];
    }
}
