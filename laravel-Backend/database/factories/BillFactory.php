<?php

namespace Database\Factories;
use App\Models\RentalRequest;
use App\Models\Bill;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bill>
 */
class BillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'payment_status' => $this->faker->randomElement(['paid', 'unpaid']), // حالة الدفع
            'payment_method' => $this->faker->randomElement(['cash', 'bank_transfer']), // طريقة الدفع
            'start_date' => $this->faker->dateTimeBetween('now', '+7 days'), // تاريخ بدء استخدام الغرض
            'end_date' => $this->faker->optional()->dateTimeBetween('+8 days', '+14 days'), // تاريخ انتهاء استخدام الغرض
            'amount' => $this->faker->randomFloat(2, 50, 500), // المبلغ المدفوع
            'rental_request_id' => RentalRequest::inRandomOrder()->first()->id, // اختيار طلب تأجير عشوائي
        ];
    }
}
