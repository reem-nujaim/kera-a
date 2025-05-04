<?php
namespace App\Http\Controllers;
use App\Models\RentalRequest;
use Illuminate\Http\Request;

class RentalRequestController extends Controller
{
    // عرض جميع الطلبات
    public function index()
{
    $rentalRequests = RentalRequest::with(['user', 'item', 'bill'])
        ->orderBy('created_at', 'desc') // ترتيب الطلبات من الأحدث إلى الأقدم
        ->get();

    return view('Admin.requests', compact('rentalRequests'));
}







    public function destroy($id)
    {
        $request = RentalRequest::findOrFail($id);
        $request->delete();

        // توجيه المستخدم إلى قائمة الطلبات بعد الحذف
        return redirect()->route('requests.index')->with('success',   __('web.Request deleted successfully'));

    }





public function requestsShow($id)
{
    $request = RentalRequest::find($id);

    if (!$request) {
        return abort(404, 'Request not found');
    }

    return view('Admin.requestShow', compact('request'));
}


}