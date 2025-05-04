<?php

namespace App\Http\Controllers\Api;
use App\Models\Item;
use App\Models\RentalRequest;
use App\Models\Bill;
use Carbon\Carbon;
use App\Models\LateFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserReportController extends Controller
{



    public function report(Request $request)
    {
        // الحصول على المستخدم الحالي
        $user = auth('sanctum')->user();
    
        // التحقق من أن المستخدم مسجل الدخول
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        $userId = $user->id;
    
        // حساب عدد الأغراض التي أضافها المستخدم
        $itemsCount = Item::where('user_id', $userId)->count();
    
        // حساب عدد طلبات الإيجار التي تلقاها المستخدم
        $rentalRequestsCount = RentalRequest::where('user_id', $userId)->count();
    
        // حساب عدد الطلبات التي تم الموافقة عليها
        $approvedRequestsCount = RentalRequest::where('user_id', $userId)
                                              ->where('status', 'approved')
                                              ->count();
    
        // حساب عدد الطلبات التي تم رفضها
        $rejectedRequestsCount = RentalRequest::where('user_id', $userId)
                                              ->where('status', 'rejected')
                                              ->count();
    
        // حساب عدد الطلبات التي هي قيد الانتظار
        $pendingRequestsCount = RentalRequest::where('user_id', $userId)
                                             ->where('status', 'pending')
                                             ->count();
    
        // حساب عدد طلبات الاستئجار التي تم الموافقة عليها من المؤجر
        $approvedRentalRequestsCount = RentalRequest::whereHas('item', function ($query) use ($userId) {
                                                    $query->where('user_id', $userId);
                                                  })
                                                  ->where('status', 'approved')
                                                  ->count();
    
        // حساب عدد طلبات الاستئجار التي تم رفضها من المؤجر
        $rejectedRentalRequestsCount = RentalRequest::whereHas('item', function ($query) use ($userId) {
                                                    $query->where('user_id', $userId);
                                                  })
                                                  ->where('status', 'rejected')
                                                  ->count();
    
        // حساب عدد الطلبات التي لازالت قيد الانتظار من المؤجر
        $pendingRentalRequestsCount = RentalRequest::whereHas('item', function ($query) use ($userId) {
                                                   $query->where('user_id', $userId);
                                                 })
                                                 ->where('status', 'pending')
                                                 ->count();
    
        // حساب عدد المتأخرات (التي لم يتم دفعها)
        $lateFeesCount = LateFee::where('paid', false)->count();
    
        // حساب إجمالي الرسوم المتأخرة
        $totalLateFeesAmount = LateFee::where('paid', false)->sum('total_fee');
    
        // تجهيز البيانات في استجابة JSON
        return response()->json([
            'items_count' => $itemsCount,
            'rental_requests_received_count' => $rentalRequestsCount,
            'approved_requests_count' => $approvedRequestsCount,
            'rejected_requests_count' => $rejectedRequestsCount,
            'pending_requests_count' => $pendingRequestsCount,
            'approved_rental_requests_count' => $approvedRentalRequestsCount,
            'rejected_rental_requests_count' => $rejectedRentalRequestsCount,
            'pending_rental_requests_count' => $pendingRentalRequestsCount,
            'late_fees_count' => $lateFeesCount,
            'total_late_fees_amount' => $totalLateFeesAmount,
        ]);
    }
    
    
    
    

}
