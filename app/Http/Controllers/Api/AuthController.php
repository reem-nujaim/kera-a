<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Notifications\AccountVerificationRequest;
use App\Notifications\AccountVerificationSuccess;
use App\Notifications\AccountVerificationFailed;
use Notification;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // التحقق من صحة البيانات
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // محاولة تسجيل الدخول
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // إنشاء توكن جديد
        $user = $request->user();
        $token = $user->createToken('auth_token')->plainTextToken;

        // إرجاع الاستجابة مع التوكن
        return response()->json([
            'message' => 'Login successful',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user_id' => $user->id
            
        ]);
    }

    public function logout(Request $request)
    {
        // حذف التوكن الحالي للمستخدم
        $request->user()->tokens()->delete();

        // الاستجابة عند نجاح تسجيل الخروج
        return response()->json([
            'message' => 'Logged out successfully'
        ], 200);
    }

    public function register(Request $request)
    {
        // التحقق من صحة البيانات المدخلة

            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'phone' => 'required|digits:9|unique:users',
                'address' => 'nullable|string',
                'user_image' => 'nullable|string',
                // 'identity_card_image_front' => 'nullable|string',
                // 'identity_card_image_back' => 'nullable|string',
                // 'identity_card_number' => 'nullable|digits_between:1,20|unique:users',
                'is_active' => 'boolean',
                'terms_accepted' => 'required|boolean',
                'is_admin' => 'boolean',
            ]);

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'phone' => $request->phone,
                'address' => $request->address,
                'user_image' => $request->user_image,
                // 'identity_card_image_front' => $request->identity_card_image_front,
                // 'identity_card_image_back' => $request->identity_card_image_back,
                // 'identity_card_number' => $request->identity_card_number,
                'is_active' => $request->is_active ?? false,
                'terms_accepted' => $request->terms_accepted,
                'is_admin' => $request->is_admin ?? false, // تضمين العمود الجديد مع القيمة الافتراضية
            ]);
            return response()->json(['message' => 'تم انشاء الحساب بنجاح.' , 'data' => $user], 201);

        }


public function verifyAccount(Request $request, $userId)
{
    // التحقق من وجود المستخدم
    $user = User::find($userId);
    
    if (!$user) {
        return response()->json(['message' => 'المستخدم غير موجود.'], 404);
    }

    // التحقق من إرسال صورة الهوية ورقم الهوية
    $validated = $request->validate([
        'identity_card_image_front' => 'required|image',
        'identity_card_image_back' => 'required|image',
        'identity_card_number' => 'required|numeric',
    ]);

    // تخزين صور الهوية في المسار المناسب
    $imageFrontPath = $request->file('identity_card_image_front')->store('identity_cards/front', 'public');
    $imageBackPath = $request->file('identity_card_image_back')->store('identity_cards/back', 'public');
    
    // تحديث بيانات المستخدم
    $user->identity_card_image_front = $imageFrontPath;
    $user->identity_card_image_back = $imageBackPath;
    $user->identity_card_number = $validated['identity_card_number'];
    $user->save();

    // إرسال إشعار إلى الإدمن
     $admin = User::role('admin')->get();
        Notification::send($admin, new AccountVerificationRequest($user));

    return response()->json(['message' => 'تم إرسال طلب التوثيق إلى الإدمن بنجاح.']);
}
        

public function approveAccountVerification(Request $request, $userId)
{
    $user = User::find($userId);

    if (!$user) {
        return response()->json(['message' => 'المستخدم غير موجود.'], 404);
    }

    // التحقق من أن صورة الهوية ورقم الهوية موجودين
    if (!$user->identity_card_image_front || !$user->identity_card_image_back || !$user->identity_card_number) {
        return response()->json(['message' => 'صورة الهوية ورقم الهوية غير موجودين.'], 400);
    }

    // التوثيق بنجاح
    $user->is_verified = true;  // تحديث حالة التوثيق
    $user->save();

    // إرسال إشعار للمستخدم
    $user->notify(new AccountVerificationSuccess($user));

    return response()->json(['message' => 'تم توثيق الحساب بنجاح.']);
}


public function getVerificationStatus(Request $request)
{
    $user = $request->user(); // جلب المستخدم المصادق عليه

    if (!$user) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    return response()->json([
        'is_verified' => (bool) $user->is_verified, // تأكد أنها `boolean`
    ], 200);
}







}


