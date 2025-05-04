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
        Schema::create('bills', function (Blueprint $table) {
            $table->id(); // المعرف الأساسي
            $table->enum('payment_status', ['paid', 'unpaid']); // حالة الدفع
            $table->enum('payment_method', ['cash', 'bank_transfer']); // طريقة الدفع
            $table->timestamp('start_date'); // تاريخ بدء استخدام الغرض
            $table->timestamp('end_date')->nullable(); // تاريخ انتهاء استخدام الغرض
            $table->decimal('amount', 10, 2); // المبلغ المدفوع
            $table->foreignId('rental_request_id')->constrained('rental_requests')->onDelete('cascade'); // المفتاح الأجنبي مع جدول rental_requests
            $table->timestamps(); // created_at و updated_at
        });

       
       
        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
