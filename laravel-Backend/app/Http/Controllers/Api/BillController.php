<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\Item;
use App\Models\RentalRequest;


class BillController extends Controller
{
    public function getAllBills()
    {
        // جلب جميع الفواتير مع تحميل الطلب والعنصر المرتبط بها دفعة واحدة لتقليل الاستعلامات
        $bills = Bill::with('rentalRequest.item')->get();
    
        // إضافة مبلغ التأمين لكل فاتورة
        $billsWithInsurance = $bills->map(function ($bill) {
            // الحصول على الطلب المرتبط بالفاتورة
            $rentalRequest = $bill->rentalRequest;
    
            // الحصول على العنصر المرتبط بالطلب
            $item = $rentalRequest ? $rentalRequest->item : null;
    
            // إذا كان هناك عنصر مرتبط بالطلب، نحصل على مبلغ التأمين من العنصر
            $insuranceAmount = $item ? $item->price_assurance : 0;
    
            // إخفاء بعض البيانات وإضافة مبلغ التأمين كمعلومات إضافية
            return $bill->makeHidden(['rentalRequest'])->toArray() + [
                'insurance_amount' => $insuranceAmount
            ];
        });
    
        // إرجاع جميع الفواتير مع مبلغ التأمين
        return response()->json([
            'bills' => $billsWithInsurance,
        ]);
    }
    
    




    // دالة لعرض فواتير مستخدم معين
    public function getUserBills($userId)
    {
        // جلب الفواتير المرتبطة بطلبات الإيجار التي تخص المستخدم (سواء كان مستأجرًا أو مالك الغرض)
        $bills = Bill::whereHas('rentalRequest', function ($query) use ($userId) {
            $query->whereHas('item', function ($itemQuery) use ($userId) {
                $itemQuery->where('user_id', $userId); // البحث عن الفواتير التي تتعلق بأغراض يملكها المستخدم
            })->orWhere('user_id', $userId); // أو الفواتير التي تخص المستخدم كمستأجر
        })->with('rentalRequest.item')->get();
    
        // إضافة مبلغ التأمين لكل فاتورة
        $billsWithInsurance = $bills->map(function ($bill) {
            // الحصول على الطلب المرتبط بالفاتورة
            $rentalRequest = $bill->rentalRequest;
    
            // الحصول على العنصر المرتبط بالطلب
            $item = $rentalRequest ? $rentalRequest->item : null;
    
            // إذا كان هناك عنصر مرتبط بالطلب، نحصل على مبلغ التأمين من العنصر
            $insuranceAmount = $item ? $item->price_assurance : 0;
    
            // إخفاء بعض البيانات وإضافة مبلغ التأمين كمعلومات إضافية
            return $bill->makeHidden(['rentalRequest'])->toArray() + [
                'insurance_amount' => $insuranceAmount
            ];
        });
    
        // إرجاع الفواتير الخاصة بالمستخدم
        return response()->json([
            'bills' => $billsWithInsurance,
        ]);
    }
    
    


}
