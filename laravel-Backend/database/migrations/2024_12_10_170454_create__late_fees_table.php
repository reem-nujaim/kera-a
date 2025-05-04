<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('late_fees', function (Blueprint $table) {
            $table->id(); // المعرف الأساسي
            $table->time('number_of_late_hours'); // عدد ساعات التأخير مع الدقائق والثواني
            $table->decimal('fee_per_late_hour', 8, 2); // الرسوم لكل ساعة تأخير
            $table->decimal('total_fee', 10, 2); // إجمالي الرسوم المتأخرة
            $table->timestamp('late_fee_date')->nullable(); // تاريخ فرض الرسوم المتأخرة مع الوقت
            $table->boolean('paid')->default(false); // عمود "تم الدفع" - الافتراضي "لا"
            $table->foreignId('rental_request_id')->constrained('rental_requests')->onDelete('cascade'); // المفتاح الأجنبي مع جدول rental_requests
            $table->timestamps(); // created_at و updated_at
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('late_fees');
    }
};
