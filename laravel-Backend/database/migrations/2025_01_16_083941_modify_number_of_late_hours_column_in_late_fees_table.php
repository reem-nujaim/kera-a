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
        Schema::table('late_fees', function (Blueprint $table) {
            // تعديل نوع البيانات لعمود number_of_late_hours إلى decimal
            $table->decimal('number_of_late_hours', 8, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('late_fees', function (Blueprint $table) {
             // إعادة نوع البيانات إلى النوع الأصلي (time)
             $table->time('number_of_late_hours')->change();
        });
    }
};
