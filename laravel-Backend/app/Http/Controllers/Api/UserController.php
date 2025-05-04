<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Item;
class UserController extends Controller
{
    //is_active

    public function updateAccountStatus(Request $request)
{
    // جلب المستخدم الحالي
    $user = $request->user();

    // التحقق من البيانات المرسلة
    $request->validate([
        'is_active' => 'required|boolean',
    ]);

    // تحديث حالة الحساب
    $user->is_active = $request->is_active;
    $user->save();

    // إرجاع استجابة نجاح مع البيانات المحدثة
    return response()->json([
        'message' => 'Account status updated successfully.',
        'user' => [
            'id' => $user->id,
            'is_active' => $user->is_active,
            'updated_at' => $user->updated_at,
        ],
    ], 200);
}


// delete user accuont
public function deactivateAccount(Request $request)
{
    // جلب المستخدم الحالي
    $user = $request->user();

    // التأكد من أن المستخدم مسجل دخوله
    if (!$user) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    // إلغاء تفعيل الحساب
    $user->is_active = false;
    $user->save();

    // إرجاع رسالة نجاح
    return response()->json(['message' => 'Account has been deactivated successfully'], 200);
}
// public function show($id)
// {
//     // جلب بيانات المستخدم من قاعدة البيانات
//     $user = User::find($id);
    
//     // التحقق إذا كان المستخدم موجودًا
//     if (!$user) {
//         return response()->json(['message' => 'User not found'], 404);
//     }

//     // إرجاع البيانات
//     return response()->json($user);
// }
// public function show($id)
// {
//     // التحقق إذا كان المستخدم مسجل دخوله
//     if (!auth()->check()) {
//         return response()->json(['message' => 'Unauthorized'], 401);
//     }

//     $user = User::find($id);

//     if (!$user) {
//         return response()->json(['message' => 'User not found'], 404);
//     }

//     return response()->json($user);
// }

// public function getUserItems($id)
//     {
//         // جلب الأغراض المرتبطة بالمستخدم
//         $items = Item::where('user_id', $id)->get();
        
//         // التحقق إذا كان المستخدم لديه أغراض
//         if ($items->isEmpty()) {
//             return response()->json(['message' => 'No items found for this user'], 404);
//         }

//         // إرجاع الأغراض
//         return response()->json($items);
//     }
}
