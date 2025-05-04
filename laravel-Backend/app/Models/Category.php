<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Category extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'en_name',
        'ar_name',
        'en_descrieption',
        'ar_description',
        'parent_id'

    ];

    /**
     * تحديد إعدادات السجل
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('categories') // اسم السجل
            ->logOnly(['en_name', 'ar_name', 'en_descrieption', 'ar_description', 'parent_id']) // الحقول المسجلة
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
                $id = $this->id; // الحصول على ID السجل

                 // الوصف باللغة العربية
                 $arabicFieldsChanged = collect($changes)
                 ->reject(fn($newValue, $field) => $field === 'updated_at')
                 ->map(function ($newValue, $field) use ($original) {
                     $oldValue = $original[$field] ?? 'غير متوفر';
                     return "{$field}: من '{$oldValue}' إلى '{$newValue}'";
                    })->join(', ');
                    $arabicDescription = "تم {$translatedEvent} الفئة بالمعرف {$id}. الحقول المعدلة: {$arabicFieldsChanged}";

                    // الوصف باللغة الإنجليزية
                    $englishFieldsChanged = collect($changes)
                        ->reject(fn($newValue, $field) => $field === 'updated_at')
                        ->map(function ($newValue, $field) use ($original) {
                            $oldValue = $original[$field] ?? 'Not available'; // القيمة الأصلية

                            return "{$field}: from '{$oldValue}' to '{$newValue}'";
                        })->join(', ');

                        $englishDescription = "The Category with ID {$id} was {$eventName}. Fields changed: {$englishFieldsChanged}";


                // العودة كوصف نصي واحد مع الفاصل بين الوصفين بالعربية والإنجليزية
                return "{$arabicDescription} | {$englishDescription}";
            })
            ->logOnlyDirty() // تسجيل التغييرات فقط
            ->dontSubmitEmptyLogs(); // عدم تسجيل السجلات الفارغة
    }


// طريقة للحصول على الاسم بناءً على اللغة
public function getNameAttribute()
{
    return app()->getLocale() === 'ar' ? $this->ar_name : $this->en_name;
}

// طريقة للحصول على الوصف بناءً على اللغة
public function getDescriptionAttribute()
{
    return app()->getLocale() === 'ar' ? $this->ar_description : $this->en_descrieption;
}





    public function items()
    {
        return $this->hasMany(Item::class);
    }


        // علاقة مع الفئة الرئيسية

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

        // علاقة مع الفئات الفرعية

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }


}




















