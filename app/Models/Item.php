<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;


use Illuminate\Database\Eloquent\Model;

class Item extends Model
{

    use HasFactory;





    protected $fillable = [
        'name',
        'description',
        'images',
        'status',
        'available',
         'admin_approval',
        'location',
        'GPS_location',
        'price_assurance',
        'delivery_method',
        'price_per_hour',
        'price_per_day',
        'quantity',
        'min_rental_duration',
        'max_rental_duration',
        'availability_hours',
        'user_id',
        'category_id'

    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    // العلاقة مع التقييمات
    public function ratings()
    {
        return $this->hasMany(Rating::class); // المنتج يحتوي على العديد من التقييمات
    }
    //العلاقة مع الطلبات
    public function rentalRequests()
    {
        return $this->hasMany(RentalRequest::class);
    }



    protected $casts = [
        'images' => 'array', // تحويل محتوى الحقل JSON إلى مصفوفة
    ];

}
