<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id(); // المعرف الأساسي
            $table->decimal('score', 2, 1); // الدرجة، يمكن أن تكون على سبيل المثال من 1 إلى 5
            $table->text('comment')->nullable(); // التعليق (اختياري)
            $table->date('review_date')->default(DB::raw('CURRENT_TIMESTAMP'));  // تاريخ التقييم
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade'); // المفتاح الأجنبي مع جدول items
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // المفتاح الأجنبي مع جدول users
            $table->timestamps(); // created_at و updated_at
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rating');
    }
};
