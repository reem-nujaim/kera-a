<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rental_requests', function (Blueprint $table) {
            $table->id(); // العمود الأساسي
            $table->timestamp('start_date'); // تاريخ بدء الطلب
            $table->timestamp('end_date')->nullable(); // تاريخ نهاية الطلب
            $table->timestamp('request_date')->default(DB::raw('CURRENT_TIMESTAMP')); // تاريخ ووقت الطلب، الافتراضي هو الوقت الحالي
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // حالة الطلب، الافتراضي "قيد الانتظار"
            $table->decimal('amount', 10, 2); // المبلغ
            $table->enum('payment_method', ['cash', 'bank_transfer']); // نوع الدفع
            $table->bigInteger('transaction_number')->nullable(); // رقم الحوالة (اختياري)
            $table->enum('payment_status', ['pending', 'completed', 'failed'])->default('pending'); // حالة الدفع، الافتراضي "قيد الانتظار"
            $table->enum('delivery_method', ['self', 'courier']); // طريقة التوصيل

            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // المفتاح الأجنبي مع جدول المستخدمين
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade'); // المفتاح الأجنبي مع جدول العناصر
            $table->timestamps(); // حقول created_at و updated_at
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_rental__request');
    }
};
