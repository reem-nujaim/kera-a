<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id(); // العمود الأساسي
            $table->string('name'); // اسم العنصر
            $table->text('description'); // وصف العنصر
            $table->json('images');// صور العنصر (عدة صور)
            $table->enum('status', ['excellent', 'good', 'acceptable', 'barely used']); // حالة العنصر
            $table->boolean('available')->default(true); // التوافر
            $table->boolean('admin_approval')->default(false); // إضافة عمود موافقة الأدمن
            $table->string('location'); // الموقع
            $table->decimal('price_assurance', 10, 2); // سعر التأمين
            $table->enum('delivery_method', ['self', 'courier']); // طريقة التوصيل
            $table->decimal('price_per_hour', 10, 2)->nullable(); // السعر بالساعة
            $table->decimal('price_per_day', 10, 2)->nullable(); // السعر باليوم
            // $table->decimal('price_per_week', 10, 2)->nullable(); // السعر بالأسبوع
            // $table->decimal('price_per_month', 10, 2)->nullable(); // السعر بالشهر
            $table->integer('quantity');
            $table->integer('min_rental_duration')->nullable(); // الحد الأدنى لمدة التأجير (بالأيام)
            $table->integer('max_rental_duration')->nullable(); // الحد الأقصى لمدة التأجير (بالأيام)
            $table->integer('availability_hours')->nullable(); // ساعات توفر الغرض (ساعات)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // المفتاح الأجنبي مع جدول المستخدمين
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade'); // المفتاح الأجنبي مع جدول الفئات
            $table->timestamps(); // الحقول الافتراضية
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
