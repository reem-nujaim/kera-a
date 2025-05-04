<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Bill extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'payment_status',
        'payment_method',
        'start_date',
        'end_date',
        'amount',
        'rental_request_id'

    ];

    protected static function boot()
    {
        parent::boot();

        // حدث عند تحديث الفاتورة
        static::updated(function ($bill) {
            if ($bill->isDirty('payment_status') && $bill->payment_status == 'paid') {
                // التحقق من أن حالة الدفع أصبحت "مدفوعة"
                $setting = Setting::first();
                if ($setting) {
                    $percentage = $bill->amount * 0.10; // حساب 10% من مبلغ الفاتورة
                    $setting->increment('app_budget', $percentage); // تحديث ميزانية التطبيق
                }
            }
        });
    }
        /**
     * تحديد إعدادات السجل
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('bills') // اسم السجل
            ->logOnly(['payment_status']) // الحقول المسجلة
            ->setDescriptionForEvent(function (string $eventName) {
                // ترجمة اسم الحدث إلى اللغة العربية
                $translatedEvent = match ($eventName) {
                    'updated' => 'تعديل',
                    default => $eventName, // في حال كان هناك حدث غير متوقع
                };

                // الوصف العربي والإنجليزي
                $arabicDescription = "تم {$translatedEvent} السند برقم: {$this->id} إلى مدفوع";
            $englishDescription = "The bill with ID: {$this->id} was {$eventName} to paied";

                return "{$arabicDescription} | {$englishDescription}";
            })
            ->logOnlyDirty() // تسجيل التغييرات فقط
            ->dontSubmitEmptyLogs(); // عدم تسجيل السجلات الفارغة
    }

    public function rental_request()
    {
        return $this->belongsTo(RentalRequest::class);
    }
    public function user()
    {
        return $this->rental_request->belongsTo(User::class, 'user_id');
    }

}