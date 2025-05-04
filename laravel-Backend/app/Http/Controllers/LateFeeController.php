<?php

namespace App\Http\Controllers;

use App\Models\LateFee;
use Illuminate\Http\Request;

class LateFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        {
            // استرجاع جميع التأخرات من جدول late_fees
            $lateFees = LateFee::orderBy('created_at', 'desc')->get();

            // تمرير البيانات إلى العرض
            return view('Admin.lateFees', compact('lateFees'));
        }
    }

    public function updateStatus(Request $request, $id)
{
    $lateFee = LateFee::findOrFail($id);

    // تحقق إذا كانت  مدفوعة بالفعل
    if ($lateFee->paid == true) {
        return redirect()->route('lateFees.index')->with('error', 'This Late Fees is already paid and cannot be changed.');
    }

    // تحديث الحالة إلى "مدفوعة"
    $lateFee->paid = true;
    $lateFee->save();

    return redirect()->route('lateFees.index')->with('success',  __('web.payment_status_updated', ['request_id' => $lateFee->rental_request_id]));
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
    public function destroy(string $id)
    {
        //
    }
}
