<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        // عرض صفحة التسجيل
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // التحقق من صحة البيانات المدخلة
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required','unique:'.User::class, 'regex:/^(77|78|73|71|70)\d{7}$/'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // إنشاء المستخدم كأدمن
        $admin = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'email_verified_at' => now(),
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'is_active' => true,
            'is_admin' => true, // تعيين المستخدم كأدمن



        ]);

        // إرسال حدث تسجيل المستخدم الجديد
        event(new Registered($admin));

        // إسناد الدور "admin" للمستخدم
        $admin->assignRole('admin');

        // تسجيل الدخول تلقائيًا بعد التسجيل
        Auth::login($admin);

        // إعادة التوجيه إلى لوحة التحكم
        return redirect()->route('dashboard')->with('success', 'تم تسجيل الأدمن بنجاح.');
    }
}







