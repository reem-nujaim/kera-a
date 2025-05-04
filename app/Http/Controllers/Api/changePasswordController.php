<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;



class ChangePasswordController extends Controller
{
//     public function changePassword(Request $request)
// {
//     // جلب المستخدم الحالي
//     $user = $request->user();

//     // التحقق من البيانات المرسلة
//     $validator = Validator::make($request->all(), [
//         'current_password' => 'required|string',
//         'new_password' => 'required|string|min:8|confirmed',
//     ]);

//     // إرجاع أخطاء التحقق إن وجدت
//     if ($validator->fails()) {
//         return response()->json(['errors' => $validator->errors()], 422);
//     }

//     // التحقق من كلمة المرور الحالية
//     if (!Hash::check($request->current_password, $user->password)) {
//         return response()->json(['message' => 'The current password is incorrect'], 400);
//     }

//     // تحديث كلمة المرور الجديدة
//     $user->password = Hash::make($request->new_password);
//     $user->save();

//     // إرجاع استجابة نجاح
//     return response()->json([
//         'message' => 'Password updated successfully',
//     ], 200);
// }
    //

    public function changePassword(Request $request)
{
    // جلب المستخدم المصادق عليه
    $user = $request->user();

    // التحقق من البيانات المرسلة
    $validator = Validator::make($request->all(), [
        'current_password' => 'required|string',
        'new_password' => 'required|string|min:8|confirmed',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // التحقق من كلمة المرور الحالية
    if (!Hash::check($request->current_password, $user->password)) {
        return response()->json(['message' => 'Current password is incorrect.'], 401);
    }

    // تحديث كلمة المرور
    $user->password = Hash::make($request->new_password);
    $user->save();

    // إرجاع استجابة نجاح
    return response()->json(['message' => 'Password changed successfully.'], 200);
}

}





