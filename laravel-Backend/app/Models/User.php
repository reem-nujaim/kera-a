<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\CausesActivity;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
  

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles,HasApiTokens, LogsActivity, Notifiable;
    use CausesActivity;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'email_verified_at',
        'password',
        'phone',
        'address',
        'user_image',
        'identity_card_image_front',
        'identity_card_image_back',
        'identity_card_number',
        'is_active',
        'terms_accepted',
        'is_admin',
        'is_verified',


    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function rental_requests()
    {
        return $this->hasMany(RentalRequest::class);
    }


    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function setting()
    {
        return $this->hasOne(Setting::class); // علاقة 1:1 من المستخدم إلى الإعدادات
    }


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('users') // اسم السجل
            ->logOnly(['is_active']) // الحقول المسجلة
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
                    $arabicDescription = "تم {$translatedEvent} المستخدم بالمعرف {$id}. الحقول المعدلة: {$arabicFieldsChanged}";

                    // الوصف باللغة الإنجليزية
                    $englishFieldsChanged = collect($changes)
                        ->reject(fn($newValue, $field) => $field === 'updated_at')
                        ->map(function ($newValue, $field) use ($original) {
                            $oldValue = $original[$field] ?? 'Not available'; // القيمة الأصلية

                            return "{$field}: from '{$oldValue}' to '{$newValue}'";
                        })->join(', ');

                        $englishDescription = "The User with ID {$id} was {$eventName}. Fields changed: {$englishFieldsChanged}";


                // العودة كوصف نصي واحد مع الفاصل بين الوصفين بالعربية والإنجليزية
                return "{$arabicDescription} | {$englishDescription}";
            })
            ->logOnlyDirty() // تسجيل التغييرات فقط
            ->dontSubmitEmptyLogs(); // عدم تسجيل السجلات الفارغة
    }

   

    // علاقة المستخدم بالطلبات التي تلقاها كمؤجر
    public function rentalRequestsReceived()
    {
        return $this->hasMany(RentalRequest::class, 'owner_id');
    }

    // علاقة المستخدم بالطلبات التي قدمها كمستأجر
    public function rentalRequestsMade()
    {
        return $this->hasMany(RentalRequest::class, 'user_id');
    }
}




