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
        Schema::create('settings', function (Blueprint $table) {
            $table->id(); // المعرف الأساسي
            $table->string('logo')->nullable(); // شعار التطبيق (رابط أو مسار الصورة)
            $table->longtext('en_about_us')->nullable(); // معلومات عن التطبيق
            $table->longtext('ar_about_us')->nullable(); // معلومات عن التطبيق
            $table->longtext('en_privacy_policy')->nullable(); // سياسة الخصوصية
            $table->longtext('ar_privacy_policy')->nullable(); // سياسة الخصوصية
            $table->decimal('app_budget', 10, 2)->nullable(); // ميزانية التطبيق
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // المفتاح الأجنبي مع جدول المستخدمين
            $table->timestamps(); // created_at و updated_at
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting');
    }
};
