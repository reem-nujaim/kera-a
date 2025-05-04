<?php

namespace Database\Factories;

use App\Models\RentalRequest;
use App\Models\User;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RentalRequest>
 */
class RentalRequestFactory extends Factory
{
    protected $model = RentalRequest::class;

    public function definition(): array
    {
        return [
            'start_date' => now(),
            'end_date' => $this->faker->optional()->dateTimeBetween('+1 day', '+14 days'),
            'request_date' => now(),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'amount' => $this->faker->randomFloat(2, 50, 500),
            'payment_method' => $this->faker->randomElement(['cash', 'bank_transfer']),
            'transaction_number' => $this->faker->unique()->numerify('########'), // 8 أرقام بالضبط
            'payment_status' => $this->faker->randomElement(['pending', 'completed', 'failed']),
            'delivery_method' => $this->faker->randomElement(['self', 'courier']),
            'user_id' => User::inRandomOrder()->first()->id,
            'item_id' => Item::inRandomOrder()->first()->id,
        ];
    }
}
