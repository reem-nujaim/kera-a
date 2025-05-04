<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Assurance extends Model
{
    use HasFactory, LogsActivity;
    protected $fillable = [
        'amount',
        'en_description',
        'ar_description',
        'is_returned',
        'item_id',
        'rental_request_id'


    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('assurances') // اسم السجل
            ->logOnly(['amount', 'en_description', 'ar_description', 'is_returned', 'item_id', 'rental_request_id']) // الحقول المسجلة
            ->setDescriptionForEvent(function (string $eventName) {
                $translatedEvent = match ($eventName) {
                    'created' => 'إنشاء',
                    'updated' => 'تعديل',
                    'deleted' => 'حذف',
                    default => $eventName,
                };

                $changes = $this->getChanges(); // القيم الجديدة
                $original = $this->getOriginal(); // القيم الأصلية
                $id = $this->id; // الحصول على ID السجل

                // الوصف باللغة العربية
                $arabicFieldsChanged = collect($changes)
                    ->reject(fn($newValue, $field) => $field === 'updated_at')
                    ->map(function ($newValue, $field) use ($original) {
                        $oldValue = $original[$field] ?? 'غير متوفر'; // القيمة الأصلية
                        if ($field === 'is_returned') {
                            $oldValue = $oldValue == 1 ? 'تم الإرجاع' : 'لم يتم الإرجاع';
                            $newValue = $newValue == 1 ? 'تم الإرجاع' : 'لم يتم الإرجاع';
                        }
                        return "{$field}: من '{$oldValue}' إلى '{$newValue}'";
                    })->join(', ');

                    $arabicDescription = "تم {$translatedEvent} التأمين بالمعرف {$id}. الحقول المعدلة: {$arabicFieldsChanged}";

                // الوصف باللغة الإنجليزية
                $englishFieldsChanged = collect($changes)
                    ->reject(fn($newValue, $field) => $field === 'updated_at')
                    ->map(function ($newValue, $field) use ($original) {
                        $oldValue = $original[$field] ?? 'Not available'; // القيمة الأصلية
                        if ($field === 'is_returned') {
                            $oldValue = $oldValue == 1 ? 'Is returned' : 'Not returned';
                            $newValue = $newValue == 1 ? 'Is returned' : 'Not returned';
                        }
                        return "{$field}: from '{$oldValue}' to '{$newValue}'";
                    })->join(', ');

                    $englishDescription = "The Assurance with ID {$id} was {$eventName}. Fields changed: {$englishFieldsChanged}";

                // العودة كوصف نصي واحد مع الفاصل بين الوصفين بالعربية والإنجليزية
                return "{$arabicDescription} | {$englishDescription}";
            })
            ->logOnlyDirty() // تسجيل التغييرات فقط
            ->dontSubmitEmptyLogs(); // عدم تسجيل السجلات الفارغة
    }

    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->useLogName('assurances') // اسم السجل
    //         ->logOnly(['amount', 'en_description', 'ar_description', 'is_returned', 'item_id', 'rental_request_id']) // الحقول المسجلة
    //         ->setDescriptionForEvent(function (string $eventName) {
    //             $translatedEvent = match ($eventName) {
    //                 'created' => 'إنشاء',
    //                 'updated' => 'تعديل',
    //                 'deleted' => 'حذف',
    //                 default => $eventName,
    //             };

    //             $changes = $this->getChanges(); // القيم الجديدة
    //             $original = $this->getOriginal(); // القيم الأصلية

    //             $fieldsChanged = collect($changes)
    //                 ->reject(function ($newValue, $field) {
    //                     return $field === 'updated_at'; // استبعاد حقل التعديل
    //                 })
    //                 ->map(function ($newValue, $field) use ($original) {
    //                     $oldValue = $original[$field] ?? (app()->getLocale() === 'ar' ? 'غير متوفر' : 'Not available'); // القيمة الأصلية أو "غير متوفرة"

    //                     // تحويل is_returned إلى نص
    //                     if ($field === 'is_returned') {
    //                         $oldValue = $oldValue == 1 ? 'نعم (Yes)' : 'لا (No)';
    //                         $newValue = $newValue == 1 ? 'نعم (Yes)' : 'لا (No)';
    //                     }

    //                     return "{$field}: من '{$oldValue}' إلى '{$newValue}'";
    //                 })->join(', ');


    //             $arabicDescription = "تم {$translatedEvent} التأمين. الحقول المعدلة: {$fieldsChanged}";
    //             $englishDescription = "The Assurance was {$eventName}. Fields changed: {$fieldsChanged}";

    //             return "{$arabicDescription} | {$englishDescription}";
    //         })
    //         ->logOnlyDirty() // تسجيل التغييرات فقط
    //         ->dontSubmitEmptyLogs(); // عدم تسجيل السجلات الفارغة
    // }




    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    public function rental_request()
    {
        return $this->belongsTo(RentalRequest::class); // علاقة طلب التأمين ينتمي لطلب الإيجار
    }
}
