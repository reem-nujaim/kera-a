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
        Schema::create('users', function (Blueprint $table) {
            $table->id();           // العمود الأساسي
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->longText('password');
            $table->string('phone', 9)->unique(); // رقم الهاتف، حتى 9 رقمًا
            $table->string('address')->nullable(); // العنوان، يمكن أن يكون فارغًا
            $table->string('user_image')->nullable(); // صورة المستخدم، مسار الصورة
            $table->string('identity_card_image_front')->nullable(); // صورة وجه البطاقة الشخصية
            $table->string('identity_card_image_back')->nullable(); // صورة ظهر البطاقة الشخصية
            $table->bigInteger('identity_card_number')->unique()->nullable(); // رقم البطاقة الشخصية، فريد
            $table->boolean('is_active')->default(false);
            $table->boolean('terms_accepted')->default(false);
             $table->boolean('is_admin')->default(false); // عمود لتحديد إذا كان المستخدم أدمن

            $table->rememberToken();
            $table->timestamps();// حقول created_at و updated_at
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
