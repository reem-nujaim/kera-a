<?php
 
 namespace App\Http\Controllers;
 
 use App\Models\Assurance;
 use App\Models\Item;
 use App\Models\RentalRequest;
 use App\Models\User;
 use Illuminate\Http\Request;
 
 class AssuranceController extends Controller
 {
     /**
      * Display a listing of the resource.
      */
     public function index()
     {
         $assurances = Assurance::with('item','rental_request.user')->get();
         return view('Admin.assurances', compact('assurances')); // عرض الفواتير في الواجهة
     }
 
     /**
      * Show the form for creating a new resource.
      */
     public function create()
     {
         $requests = RentalRequest::with('item','user')->get(); // جلب جميع الطلبات
         return view('Admin.assurancesAdd', compact('requests'));
     }
 
     /**
      * Store a newly created resource in storage.
      */
     public function store(Request $request)
 {
     $validated = $request->validate([
         'amount' => 'required|numeric',
         'en_description' => 'required|string',
         'ar_description' => 'required|string',
         'is_returned' => 'boolean',
         'item_id' => 'required|exists:items,id',
         'rental_request_id' => 'required|exists:rental_requests,id',
     ]);
 
     // تحقق من عدم وجود تأمين لنفس الطلب
     $existingAssurance = Assurance::where('rental_request_id', $request->rental_request_id)->first();
 
     if ($existingAssurance) {
         return redirect()->route('assurances.index')
             ->with('error', __('web.An assurance already exists for this rental request.'));
     }
 
     // إنشاء التأمين الجديد
     Assurance::create([
         'amount' => $request->amount,
         'en_description' => $request->en_description,
         'ar_description' => $request->ar_description,
         'is_returned' => $request->is_returned ?? false,
         'item_id' => $request->item_id,
         'rental_request_id' => $request->rental_request_id,
     ]);
 
     return redirect()->route('assurances.index')
         ->with('success', __('web.Assurance added successfully!'));
 }
 
 
 
 
     /**
      * Display the specified resource.
      */
     public function show(Assurance $assurance)
     {
         return view('Admin.assurances', compact('assurance'));
     }
 
     /**
      * Show the form for editing the specified resource.
      */
     public function edit($id)
 {
     $assurance = Assurance::with('item', 'rental_request.user')->findOrFail($id); // تحميل العلاقات
     $requests = RentalRequest::with('item', 'user')->get();
     return view('Admin.assurancesEdit', compact('assurance', 'requests'));
 }
 
 
     /**
      * Update the specified resource in storage.
      */
     public function update(Request $request, $id)
 {
     $assurance = Assurance::findOrFail($id);
     $validated = $request->validate([
         'amount' => 'required|numeric',
         'en_description' => 'required|string',
         'ar_description' => 'required|string',
         'is_returned' => 'boolean',
         'item_id' => 'required|exists:items,id', // تحقق من وجود الـ item في جدول items
         'rental_request_id' => 'required|exists:rental_requests,id', // تحقق من وجود الـ rental_request في جدول rental_requests
     ]);
     // تحقق من البيانات المطلوبة في الفورم
     $assurance->update([
         'amount' => $request->input('amount'),
         'en_description' => $request->input('en_description'),
         'ar_description' => $request->input('ar_description'),
         'item_id' => $request->input('item_id'),
         'rental_request_id' => $request->input('rental_request_id'),
     ]);
 
     return redirect()->route('assurances.index')->with('success', __('web.Assurance updated successfully'));
 }
 
 // تغيير الحالة الى تم الارجاع
 public function updateStatus(Request $request, $id)
 {
 
     $assurance = Assurance::findOrFail($id);
 
     if ($assurance->is_returned) {
         logger('Assurance is already returned.');
         return redirect()->route('assurances.index')->with('error', __('web.This assurance is already returned.'));
     }
 
     $assurance->is_returned = true;
     $assurance->save();
 
 
     return redirect()->route('assurances.index')->with('success', __('web.return_status_updated', ['assurance_id' => $assurance->id]));
 }
 
 
     /**
      * Remove the specified resource from storage.
      */
     public function destroy($id)
 {
     $assurance = Assurance::findOrFail($id);
     $assurance->delete();
 
     return redirect()->route('assurances.index')->with('success', __('web.Assurance deleted successfully!'));
 }
 }