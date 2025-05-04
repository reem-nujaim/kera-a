<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\PasswordResetLink;


class ForgotPasswordController extends Controller
{
    public function sendPasswordResetLink(Request $request)
{
    // التحقق من وجود البريد الإلكتروني في النظام
    $request->validate(['email' => 'required|email']);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json(['message' => 'No user found with this email address'], 404);
    }

    // توليد رمز إعادة تعيين كلمة المرور
    $token = Str::random(60);

    // حفظ رمز التحقق في قاعدة البيانات
    $user->password_reset_token = $token;
    $user->save();

    // إرسال الرابط إلى البريد الإلكتروني
    $resetLink = url(route('password.reset', ['token' => $token], false)); // بناء الرابط باستخدام رمز التحقق

    // إرسال البريد الإلكتروني للمستخدم مع الرابط
    Mail::to($user->email)->send(new PasswordResetLink($resetLink));

    return response()->json(['message' => 'Password reset link sent to your email address','reset_link' => $resetLink,], 200,);
    
    
}


public function resetPassword(Request $request)
{
    $request->validate([
        'token' => 'required|string',
        'password' => 'required|string|min:8|confirmed',
    ]);

    // البحث عن المستخدم باستخدام الرمز
    $user = User::where('password_reset_token', $request->token)->first();

    if (!$user) {
        return response()->json(['message' => 'Invalid token'], 400);
    }

    // تحديث كلمة المرور
    $user->password = bcrypt($request->password);
    $user->password_reset_token = null;  // مسح رمز التحقق بعد التحديث
    $user->save();

    return response()->json(['message' => 'Password reset successfully'], 200);
}

}
