<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Setting extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'logo',
        'en_about_us',
        'ar_about_us',
        'en_privacy_policy',
        'ar_privacy_policy',
        'app_budget',
        'user_id'

    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('setting') // اسم السجل
            ->logOnly(['logo', 'en_about_us', 'ar_about_us', 'en_privacy_policy', 'ar_privacy_policy']) // الحقول المسجلة
            ->setDescriptionForEvent(function (string $eventName) {
                // ترجمة اسم الحدث إلى اللغة العربية
                $translatedEvent = match ($eventName) {
                    'created' => 'إنشاء',
                    'updated' => 'تعديل',
                    'deleted' => 'حذف',
                    default => $eventName, // في حال كان هناك حدث غير متوقع
                };
                $changes = $this->getChanges(); // القيم الجديدة
                $original = $this->getOriginal(); // القيم الأصلية

                 // الوصف باللغة العربية
                 $arabicFieldsChanged = collect($changes)
                 ->reject(fn($newValue, $field) => $field === 'updated_at')
                 ->map(function ($newValue, $field) use ($original) {
                     $oldValue = $original[$field] ?? 'غير متوفر';
                     return "{$field}: من '{$oldValue}' إلى '{$newValue}'";
                    })->join(', ');
                    $arabicDescription = "تم {$translatedEvent} الإعدادات. الحقول المعدلة: {$arabicFieldsChanged}";

                    // الوصف باللغة الإنجليزية
                    $englishFieldsChanged = collect($changes)
                        ->reject(fn($newValue, $field) => $field === 'updated_at')
                        ->map(function ($newValue, $field) use ($original) {
                            $oldValue = $original[$field] ?? 'Not available'; // القيمة الأصلية

                            return "{$field}: from '{$oldValue}' to '{$newValue}'";
                        })->join(', ');

                    $englishDescription = "The Setting was {$eventName}. Fields changed: {$englishFieldsChanged}";


                // العودة كوصف نصي واحد مع الفاصل بين الوصفين بالعربية والإنجليزية
                return "{$arabicDescription} | {$englishDescription}";
            })
            ->logOnlyDirty() // تسجيل التغييرات فقط
            ->dontSubmitEmptyLogs(); // عدم تسجيل السجلات الفارغة
    }

    public function user()
    {
        return $this->belongsTo(User::class); // علاقة 1:1 من المستخدم إلى الإعدادات
    }


}
