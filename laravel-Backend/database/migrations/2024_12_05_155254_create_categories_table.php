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
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); 
            $table->string('en_name');
            $table->string('ar_name'); 
            $table->text('en_descrieption');
            $table->text('ar_description'); 
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('cascade'); // ربط الفئة بالفئة الرئيسية
            $table->timestamps(); // الحقول الافتراضية created_at و updated_at
        });
           
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
