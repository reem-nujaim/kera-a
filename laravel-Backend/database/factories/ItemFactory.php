<?php

namespace Database\Factories;
use App\Models\Item;
use App\Models\Category;





use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    protected $model = Item::class; // تأكد من ربط الموديل هنا

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // return [
        //     'name' => $this->faker->word(), // اسم العنصر (كلمة عشوائية)
        //     'description' => $this->faker->text(200), // وصف عشوائي (200 حرف تقريباً)
        //     'images' => $this->faker->imageUrl(640, 480, 'products'), // رابط لصورة عشوائية
        //     'status' => $this->faker->randomElement(['available', 'unavailable']), // حالة العنصر (متاح أو غير متاح)
        //     'available' => $this->faker->boolean(80), // هل العنصر متاح؟ (80% فرصة أن يكون متاحًا)
        //     'location' => $this->faker->city(), // الموقع (مدينة عشوائية)
        //     'price_assurance' => $this->faker->randomFloat(2, 10, 500), // مبلغ التأمين (رقم عشري بين 10 و 500)
        //     'delivery_method' => $this->faker->randomElement(['pickup', 'delivery']), // طريقة التوصيل (استلام أو توصيل)
        //     'price_per_hour' => $this->faker->randomFloat(2, 5, 50), // السعر لكل ساعة (رقم عشري بين 5 و 50)
        //     'price_per_day' => $this->faker->randomFloat(2, 20, 150), // السعر لكل يوم (رقم عشري بين 20 و 150)
        //     'quantity' => $this->faker->numberBetween(1, 100), // الكمية المتاحة (عدد عشوائي بين 1 و 100)
        //     'min_rental_duration' => $this->faker->numberBetween(1, 7), // أقل مدة للإيجار (عدد عشوائي بين 1 و 7 أيام)
        //     'max_rental_duration' => $this->faker->numberBetween(7, 30), // أكبر مدة للإيجار (عدد عشوائي بين 7 و 30 يوم)
        //     'availability_hours' => $this->faker->time('H:i', 'now'), // ساعات التوفر (وقت عشوائي)
        //     'user_id' => \App\Models\User::inRandomOrder()->first()->id, // معرف المستخدم (اختيار عشوائي من المستخدمين الموجودين)
        //     'category_id' => \App\Models\Category::inRandomOrder()->first()->id, // معرف الفئة (اختيار عشوائي من الفئات الموجودة)
        // ];

//--------------------------------------------------------------------------------------------------------------------------//


        // التأكد من وجود فئة "الملابس" أو إنشاؤها
$clothingCategory = Category::firstOrCreate([
    'name' => 'Clothing'
]);

// التأكد من وجود فئة "الفساتين" أو إنشاؤها
$dressesCategory = Category::firstOrCreate([
    'name' => 'Dresses',
    'parent_id' => $clothingCategory->id
]);

return [
    'name' => $this->faker->word(),
    'description' => $this->faker->sentence(),
    'images' => $this->faker->imageUrl(),
    'status' => $this->faker->randomElement(['excellent', 'good', 'acceptable']),
    'available' => $this->faker->boolean(),
    'location' => $this->faker->address(),
    'price_assurance' => $this->faker->randomFloat(2, 50, 500),
    'delivery_method' => $this->faker->randomElement(['self', 'courier']),
    'price_per_hour' => $this->faker->randomFloat(2, 10, 100),
    'price_per_day' => $this->faker->randomFloat(2, 50, 200),
    'quantity' => $this->faker->numberBetween(1, 10),
    'min_rental_duration' => $this->faker->numberBetween(1, 7),
    'max_rental_duration' => $this->faker->numberBetween(7, 30),
    'availability_hours' => $this->faker->numberBetween(1, 24),
    'user_id' => 1,  // يمكنك ربطها بمستخدم عشوائي
    'category_id' => $dressesCategory->id,  // ربط العنصر بفئة "الفساتين"
];

//--------------------------------------------------------------------------------------------------------------------------//



    }
}








