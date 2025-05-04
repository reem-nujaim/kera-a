<?php

namespace Database\Factories;
use App\Models\Category; // استيراد موديل Category
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class; // تأكد من ربط الموديل هنا


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {





        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),  // إضافة وصف عشوائي
            'parent_id' => null,  // إذا كانت الفئة رئيسية
            //     'parent_id' => $this->faker->optional()->randomElement(Category::pluck('id')->toArray()), // قيمة عشوائية أو null

        ];


        //--------------------------------------------------------------------------------------------------------------------------//



    }
}







