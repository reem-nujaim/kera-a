<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * عرض قائمة المستخدمين.
     */
    public function index()
    {
        // جلب المستخدمين فقط الذين ليسوا مدراء (is_admin = false)
        $users = User::where('is_admin', false)->get();
        
        // عرض المستخدمين في صفحة Blade
        return view('admin.customers', compact('users'));
    }

    /**
     * تغيير حالة المستخدم بين التفعيل والتعطيل.
     */
    public function toggleStatus($id)
    {
        // جلب المستخدم بناءً على الـ ID
        $user = User::findOrFail($id);

        // تغيير حالة is_active
        $user->is_active = !$user->is_active;
        $user->save();

        // التحقق إذا كانت الحالة الآن "مفعل" أو "غير مفعل"
        $statusMessage = $user->is_active ? __('web.User has been activated successfully!') : __('web.User has been deactivated successfully!');
        $statusColor = $user->is_active ? 'bg-green-500' : 'bg-red-500';

        // إعادة التوجيه مع رسالة النجاح
        return redirect()->route('customers.index')->with('success', ['message' => $statusMessage, 'color' => $statusColor]);
    }

    /**
     * حذف مستخدم.
     */

    public function destroy($id)
{
    $user = User::findOrFail($id);
    $user->delete();

    // إعادة التوجيه مع رسالة النجاح بعد الحذف
    return redirect()->route('customers.index')->with('success', [
        'message' => __('web.User deleted successfully!'),
        'color' => 'bg-green-500'
    ]);

}



public function showVerificationPage($id)
{
    $user = User::findOrFail($id);
    return view('admin.verification', compact('user'));
}




public function toggleVerification($id)
{
    $user = User::findOrFail($id);
    // تغيير حالة التوثيق
    $user->is_verified = !$user->is_verified;
    $user->save();

    // تحديد الرسالة واللون بناءً على حالة التوثيق
    // $verificationMessage = $user->is_verified ? __('User has been verified successfully') : __('User has been unverified successfully');


    $messageKey = $user->is_verified ? 'User has been verified successfully' : 'User has been unverified successfully';

    return redirect()->back()->with('success', $messageKey);
    // إعادة التوجيه مع الرسالة
    // return redirect()->back()->with('success', ['message' => $verificationMessage]);
}





}
