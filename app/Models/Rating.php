<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Rating extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'score',
        'comment',
        'review_date',
         'hidden',
        'item_id',
        'user_id'


    ];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('ratings') // اسم السجل
            ->logOnly(['hidden']) // الحقول المسجلة
            ->setDescriptionForEvent(function (string $eventName) {
                // ترجمة اسم الحدث إلى اللغة العربية
                $translatedEvent = match ($eventName) {
                    // 'created' => 'إنشاء',
                    'updated' => 'تعديل',
                    'deleted' => 'حذف',
                    default => $eventName, // في حال كان هناك حدث غير متوقع
                };
                $changes = $this->getChanges(); // القيم الجديدة
                $original = $this->getOriginal(); // القيم الأصلية
                $id = $this->id; // الحصول على ID السجل

                 // الوصف باللغة العربية
                 $arabicFieldsChanged = collect($changes)
                 ->reject(fn($newValue, $field) => $field === 'updated_at')
                 ->map(function ($newValue, $field) use ($original) {
                     $oldValue = $original[$field] ?? 'غير متوفر';
                     return "{$field}: من '{$oldValue}' إلى '{$newValue}'";
                    })->join(', ');
                    $arabicDescription = "تم {$translatedEvent} التقييم بالمعرف {$id}. الحقول المعدلة: {$arabicFieldsChanged}";

                    // الوصف باللغة الإنجليزية
                    $englishFieldsChanged = collect($changes)
                        ->reject(fn($newValue, $field) => $field === 'updated_at')
                        ->map(function ($newValue, $field) use ($original) {
                            $oldValue = $original[$field] ?? 'Not available'; // القيمة الأصلية

                            return "{$field}: from '{$oldValue}' to '{$newValue}'";
                        })->join(', ');

                        $englishDescription = "The Rating with ID {$id} was {$eventName}. Fields changed: {$englishFieldsChanged}";


                // العودة كوصف نصي واحد مع الفاصل بين الوصفين بالعربية والإنجليزية
                return "{$arabicDescription} | {$englishDescription}";
            })
            ->logOnlyDirty() // تسجيل التغييرات فقط
            ->dontSubmitEmptyLogs(); // عدم تسجيل السجلات الفارغة
    }

    public function user()
    {
        return $this->belongsTo(User::class); // التقييم ينتمي إلى مستخدم واحد
    }

    public function item()
    {
        return $this->belongsTo(Item::class); // التقييم ينتمي إلى عنصر واحد
    }




}
