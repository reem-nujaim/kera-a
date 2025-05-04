<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\User;
use App\Notifications\BillNotification;
use App\Notifications\NewRentalRequestNotification;
use App\Notifications\RentalRequestApproved;
use App\Notifications\RentalRequestRejected;
use App\Models\RentalRequest;
use App\Models\Item;
use App\Notifications\TransactionReviewNotification;
use Notification;
use App\Models\Assurance;





class RentalRequestController extends Controller
{
    //إنشاء طلب تأجير جديد
    public function store(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank_transfer',
            'transaction_number' => 'nullable|numeric',
            'delivery_method' => 'required|in:self,courier',
            'user_id' => 'required|exists:users,id',
            'item_id' => 'required|exists:items,id',
        ]);

        $rentalRequest = RentalRequest::create($validated);


         // الحصول على العنصر المرتبط بالطلب
$item = Item::where('id', $rentalRequest->item_id)->first();  // استخدام item_id لربط الطلب بالعنصر

// إذا كان هناك عنصر مرتبط بالطلب، نحصل على مبلغ التأمين من العنصر
$insuranceAmount = $item ? $item->price_assurance : 0;

    
          // إنشاء الفاتورة بعد تأكيد الطلب
         $bill = Bill::create([
        'payment_status' => 'unpaid', // يمكن تعديلها حسب الحالة
        'payment_method' => $validated['payment_method'],
        'start_date' => $validated['start_date'],
        'end_date' => $validated['end_date'],
        'amount' => $validated['amount'],
        'rental_request_id' => $rentalRequest->id, // ربط الفاتورة بالطلب
    ]);

    // إرسال إشعار للإدمن بوجود فاتورة جديدة
       $admin = User::role('admin')->get();
        Notification::send($admin, new BillNotification($bill));

    
 // إشعار المؤجر
 $item = Item::find($validated['item_id']);
 $owner = User::find($item->user_id); // الحصول على المؤجر

 if ($owner) {
     $owner->notify(new NewRentalRequestNotification($item)); // إرسال الإشعار
 }

 
 return response()->json([
    'message' => 'تم إنشاء طلب الإيجار وإرسال إشعار إلى المؤجر والادمن',
    'data' => $rentalRequest,
    'bill' => [
        'payment_status' => $bill->payment_status,
        'payment_method' => $bill->payment_method,
        'start_date' => $bill->start_date,
        'end_date' => $bill->end_date,
        'amount' => $bill->amount, // المبلغ الإجمالي في الفاتورة
        'rental_request_id' => $bill->rental_request_id,
        'id' => $bill->id,
        'insurance_amount' => $insuranceAmount, // إضافة مبلغ التأمين هنا داخل بيانات الفاتورة
    ]
], 201);


        
    }
//عرض جميع طلبات التأجير
public function index()
{
    $rentalRequests = RentalRequest::all();

    return response()->json(['data' => $rentalRequests]);
    
  
    
}

// عرض طلب تأجير محدد
public function show($id)
{
    $rentalRequest = RentalRequest::find($id);

    if (!$rentalRequest) {
        return response()->json(['message' => 'Rental request not found.'], 404);
    }

    return response()->json(['data' => $rentalRequest]);
}

//تحديث طلب تأجير
public function update(Request $request, $id)
{
    $rentalRequest = RentalRequest::find($id);

    if (!$rentalRequest) {
        return response()->json(['message' => 'Rental request not found.'], 404);
    }

    $validated = $request->validate([
        'start_date' => 'sometimes|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'amount' => 'sometimes|numeric|min:0',
        'payment_method' => 'sometimes|in:cash,bank_transfer',
        'transaction_number' => 'nullable|numeric',
        'delivery_method' => 'sometimes|in:self,courier',
        'status' => 'sometimes|in:pending,approved,rejected',
        'payment_status' => 'sometimes|in:pending,completed,failed',
    ]);

    $rentalRequest->update($validated);

// إرسال إشعار للمستأجر بناءً على حالة الطلب
$tenant = User::find($rentalRequest->user_id);
if ($validated['status'] === 'approved') {
    $tenant->notify(new RentalRequestApproved($rentalRequest));
} elseif ($validated['status'] === 'rejected') {
    $tenant->notify(new RentalRequestRejected($rentalRequest));
}

    return response()->json(['message' => 'Rental request updated successfully.', 'data' => $rentalRequest]);
}

// حذف طلب استئجار

public function destroy($id)
{
    $rentalRequest = RentalRequest::find($id);

    if (!$rentalRequest) {
        return response()->json(['message' => 'Rental request not found.'], 404);
    }

    $rentalRequest->delete();
    return response()->json(['message' => 'Rental request deleted successfully.']);
}

public function getReservedDates($itemId)
{
    $reservations = RentalRequest::where('item_id', $itemId)
        ->get(['start_date', 'end_date']);

    return response()->json($reservations);
}

######new
public function getItemRentalRequests($itemId)
{
    // ✅ جلب جميع طلبات الإيجار المتعلقة بالغرض المحدد
    $rentalRequests = RentalRequest::where('item_id', $itemId)
        ->where('status', 'approved') // ✅ فقط الطلبات المقبولة
        ->get(['start_date', 'end_date']);

    return response()->json($rentalRequests);
}

public function getUserRentalRequests($id)
{
    try {
        $requests = RentalRequest::userRentalRequests($id)->get();

        return response()->json([
            'success' => true,
            'message' => 'تم جلب طلبات الإيجار بنجاح',
            'data' => $requests
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'حدث خطأ أثناء جلب الطلبات',
            'error' => $e->getMessage()
        ], 500);
    }
}

}







