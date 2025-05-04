<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function profile(Request $request)
    {
        // جلب بيانات المستخدم الحالي
        $user = $request->user();

        // إرجاع البيانات كـ JSON
        return response()->json([
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'address' => $user->address,
            'user_image' => $user->user_image,
            'identity_card_number' => $user->identity_card_number,
            'is_active' => $user->is_active,
            'terms_accepted' => $user->terms_accepted,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ]);
    }


    //     // تحديث البروفايل
    // public function updateProfile(Request $request)
    // {
    //     // جلب المستخدم الحالي
    //     $user = $request->user();

    //     // التحقق من البيانات المرسلة
    //     $validator = Validator::make($request->all(), [
    //         'first_name' => 'string|max:255',
    //         'last_name' => 'string|max:255',
    //         'email' => 'email|unique:users,email,' . $user->id,
    //         'phone' => 'string|size:9|unique:users,phone,' . $user->id,
    //         'address' => 'string|nullable',
    //         'user_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048|nullable',
    //         'identity_card_image_front' => 'image|mimes:jpeg,png,jpg,gif|max:2048|nullable',
    //         'identity_card_image_back' => 'image|mimes:jpeg,png,jpg,gif|max:2048|nullable',
    //     ]);

    //     // إرجاع أخطاء التحقق إن وجدت
    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //    // تحديث البيانات
    //     $user->first_name = $request->first_name ?? $user->first_name;
    //     $user->last_name = $request->last_name ?? $user->last_name;
    //     $user->email = $request->email ?? $user->email;
    //     $user->phone = $request->phone ?? $user->phone;
    //     $user->address = $request->address ?? $user->address;



    //     // تحديث الصور إذا تم رفعها
    //     if ($request->hasFile('user_image')) {
    //         $user->user_image = $request->file('user_image')->store('images/users', 'public');
    //     }

    //     if ($request->hasFile('identity_card_image_front')) {
    //         $user->identity_card_image_front = $request->file('identity_card_image_front')->store('images/cards', 'public');
    //     }

    //     if ($request->hasFile('identity_card_image_back')) {
    //         $user->identity_card_image_back = $request->file('identity_card_image_back')->store('images/cards', 'public');
    //     }

    //     // حفظ التغييرات
    //     $user->save();

    //     //إرجاع البيانات المحدثة
    //     return response()->json([
    //         'message' => 'Profile updated successfully',
    //         'user' => [
    //             'id' => $user->id,
    //             'first_name' => $user->first_name,
    //             'last_name' => $user->last_name,
    //             'email' => $user->email,
    //             'phone' => $user->phone,
    //             'address' => $user->address,
    //             'user_image' => $user->user_image,
    //             'identity_card_image_front' => $user->identity_card_image_front,
    //             'identity_card_image_back' => $user->identity_card_image_back,
    //             'updated_at' => $user->updated_at,
    //         ],
    //     ]);


    // }


    public function updateProfile(Request $request)
{
    // جلب المستخدم الحالي
    $user = $request->user();

    // التحقق من البيانات المرسلة
    $validator = Validator::make($request->all(), [
        'first_name' => 'string|max:255',
        'last_name' => 'string|max:255',
        'email' => 'email|unique:users,email,' . $user->id,
        'phone' => 'string|size:9|unique:users,phone,' . $user->id,
        'address' => 'string|nullable',
        'user_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048|nullable',
        'identity_card_image_front' => 'image|mimes:jpeg,png,jpg,gif|max:2048|nullable',
        'identity_card_image_back' => 'image|mimes:jpeg,png,jpg,gif|max:2048|nullable',
    ]);

    // إرجاع أخطاء التحقق إن وجدت
    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // تحديث البيانات
    $user->first_name = $request->first_name ?? $user->first_name;
    $user->last_name = $request->last_name ?? $user->last_name;
    $user->email = $request->email ?? $user->email;
    $user->phone = $request->phone ?? $user->phone;
    $user->address = $request->address ?? $user->address;

    // تحديث الصور إذا تم رفعها
    if ($request->hasFile('user_image')) {
        $user->user_image = $request->file('user_image')->store('images/users', 'public');
    }

    if ($request->hasFile('identity_card_image_front')) {
        $user->identity_card_image_front = $request->file('identity_card_image_front')->store('images/cards', 'public');
    }

    if ($request->hasFile('identity_card_image_back')) {
        $user->identity_card_image_back = $request->file('identity_card_image_back')->store('images/cards', 'public');
    }

    // حفظ التغييرات
    if (!$user->save()) {
        return response()->json(['message' => 'Failed to update profile'], 500);
    }

    // تحديث البيانات المحفوظة
    $user = $user->fresh();

    // إرجاع البيانات المحدثة
    return response()->json([
        'message' => 'Profile updated successfully',
        'user' => [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'address' => $user->address,
            'user_image' => $user->user_image ? asset('storage/' . $user->user_image) : null,
            'identity_card_image_front' => $user->identity_card_image_front ? asset('storage/' . $user->identity_card_image_front) : null,
            'identity_card_image_back' => $user->identity_card_image_back ? asset('storage/' . $user->identity_card_image_back) : null,
            'updated_at' => $user->updated_at,
        ],
    ]);
}

}
