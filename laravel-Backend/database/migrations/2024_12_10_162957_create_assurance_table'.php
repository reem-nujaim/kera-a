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
        Schema::create('assurances', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 10, 2); // المبلغ النقدي
            $table->text('en_description'); // وصف التأمين (يمكنك إضافة نوع التأمين هنا مثل "ذهب" أو "نقدي" أو "سلاح")
            $table->text('ar_description'); // وصف التأمين (يمكنك إضافة نوع التأمين هنا مثل "ذهب" أو "نقدي" أو "سلاح")
            // $table->date('date'); // تاريخ التأمين
            $table->boolean('is_returned')->default(false); // هل التأمين تم استرجاعه (افتراضيًا يكون "لا")
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade'); // المفتاح الأجنبي مع جدول items
            $table->foreignId('rental_request_id')->constrained('rental_requests')->onDelete('cascade'); // المفتاح الأجنبي مع جدول rental_requests
            $table->timestamps();
        });
    
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assurances');
    }
};
