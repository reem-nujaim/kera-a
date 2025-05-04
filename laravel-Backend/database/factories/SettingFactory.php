<?php

namespace Database\Factories;
use App\Models\User;
use App\Models\Setting;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Setting>
 */
class SettingFactory extends Factory
{    //protected $model = Setting::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'logo' => $this->faker->imageUrl(200, 200, 'logo', true), // توليد رابط صورة عشوائية للشعار
            'en_about_us' => $this->faker->paragraph(3), // توليد نص عشوائي عن التطبيق
            'ar_about_us' => $this->faker->paragraph(3), // توليد نص عشوائي عن التطبيق
            'en_privacy_policy' => $this->faker->paragraph(4), // توليد نص عشوائي لسياسة الخصوصية
            'ar_privacy_policy' => $this->faker->paragraph(4), // توليد نص عشوائي لسياسة الخصوصية
            'app_budget' => $this->faker->optional()->randomFloat(2, 1000, 50000), // ميزانية التطبيق اختيارية بين 1000 و 50000
            'user_id' => User::inRandomOrder()->first()->id, // اختيار مستخدم عشوائي
        ];
    }
}
