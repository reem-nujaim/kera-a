<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\LateFee;

class RentalRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_date',
        'end_date',
        'request_date',
        'status',
        'amount',
        'payment_method',
        'transaction_number',
        'payment_status',
        'delivery_method',
        'user_id',
        'item_id',
    ];

    // /**
    //  * دالة لحساب المتأخرات بناءً على الوقت
    //  */
    // public function calculateLateFees()
    // {
    //     // التحقق إذا كانت هناك فترة تأخير
    //     if ($this->end_date && Carbon::now()->greaterThan($this->end_date)) {
    //         // احسب عدد ساعات التأخير بشكل دقيق
    //         $lateMinutes = Carbon::now()->diffInMinutes($this->end_date);
    //         $lateHours = $lateMinutes / 60; // تحويل الدقائق إلى ساعات

    //         // جلب قيمة fee_per_hour من جدول items عبر العلاقة
    //         $feePerHour = $this->item->price_per_hour ?? 0; // التأكد من وجود fee_per_hour

    //         // حساب الرسوم المتأخرة
    //         $totalFee = $lateHours * $feePerHour;

    //         // تحقق من وجود سجل متأخر بالفعل
    //     $existingLateFee = $this->latefee;

    //     if (!$existingLateFee) {
    //         // إضافة سجل في جدول `late_fees`
    //         LateFee::create([
    //             'number_of_late_hours' => $lateHours,
    //             'fee_per_late_hour' => $feePerHour,
    //             'total_fee' => $totalFee,
    //             'late_fee_date' => Carbon::now(),
    //             'rental_request_id' => $this->id,
    //             'paid' => false,
    //         ]);
    //     }
    //     }
    // }

    /**
     * العلاقة مع جدول Item
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * العلاقة مع جدول User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * العلاقة مع جدول LateFee
     */
    public function latefee()
    {
        return $this->hasOne(LateFee::class);
    }

    /**
     * العلاقة مع جدول Bill
     */
    public function bill()
    {
        return $this->hasOne(Bill::class);
    }

    /**
     * العلاقة مع جدول Assurance
     */
    public function assurance()
    {
        return $this->hasOne(Assurance::class);
    }


  protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'request_date' => 'datetime',
    ];


    
}
