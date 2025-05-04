<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class LateFee extends Model
{

    use HasFactory, LogsActivity;


    protected $fillable = [
        'number_of_late_hours',
        'fee_per_late_hour',
        'total_fee',
        'late_fee_date',
        'paid',
        'rental_request_id'

    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('lateFees') // اسم السجل
            ->logOnly(['paid']) // الحقول المسجلة
            ->setDescriptionForEvent(function (string $eventName) {
                // ترجمة اسم الحدث إلى اللغة العربية
                $translatedEvent = match ($eventName) {
                    'updated' => 'تعديل',
                    default => $eventName, // في حال كان هناك حدث غير متوقع
                };

                // الوصف العربي والإنجليزي
                $arabicDescription = "تم {$translatedEvent} رسوم المتأخر برقم: {$this->id} إلى مدفوع";
            $englishDescription = "The Late Fee with ID: {$this->id} was {$eventName} to paied";

                return "{$arabicDescription} | {$englishDescription}";
            })
            ->logOnlyDirty() // تسجيل التغييرات فقط
            ->dontSubmitEmptyLogs(); // عدم تسجيل السجلات الفارغة
    }




    public function RentalRequest()
    {
        return $this->belongsTo(RentalRequest::class);
    }
}
