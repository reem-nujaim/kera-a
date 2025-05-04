<?php
 
 namespace App\Http\Controllers;
 
 use App\Models\Bill;
 use Illuminate\Http\Request;
 
 class BillController extends Controller
 {
     /**
      * Display a listing of the resource.
      */
     public function index()
 {
     $bills = Bill::with('rental_request.user', 'rental_request.assurance')->get();
     return view('Admin.bills', compact('bills'));
 }
 
 
 public function updateStatus(Request $request, $id)
 {
     $bill = Bill::findOrFail($id);
 
     // تحقق إذا كانت الفاتورة مدفوعة بالفعل
     if ($bill->payment_status === 'paid') {
         return redirect()->route('bills.index')->with('error', 'This bill is already paid and cannot be changed.');
     }
 
     // تحديث الحالة إلى "مدفوعة"
     $bill->payment_status = 'paid';
     $bill->save();
 
     return redirect()->route('bills.index')->with('success',  __('web.payment_status_updated', ['request_id' => $bill->rental_request_id]));
 }
 
 
 
     /**
      * Show the form for creating a new resource.
      */
     public function create()
     {
         //
     }
 
     /**
      * Store a newly created resource in storage.
      */
     public function store(Request $request)
     {
         //
     }
 
     /**
      * Display the specified resource.
      */
     public function show(string $id)
     {
         //
     }
 
     /**
      * Show the form for editing the specified resource.
      */
     public function edit(string $id)
     {
         //
     }
 
     /**
      * Update the specified resource in storage.
      */
     public function update(Request $request, string $id)
     {
         //
     }
 
     /**
      * Remove the specified resource from storage.
      */
     public function destroy($id)
 {
     $bill = Bill::findOrFail($id);
 
     if (!$bill) {
         return redirect()->route('bills.index')->with('error', 'Bill not found');
     }
 
     $bill->delete();
 
     return redirect()->route('bills.index')->with('success', 'Bill deleted successfully');
 }
 
 }