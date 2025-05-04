<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // تحقق إذا كان المستخدم مسجلاً
        if (Auth::check()) {
            // تحقق إذا كان لديه دور "admin"
            if (Auth::user()->hasRole('admin')) {
                return $next($request);
            }

            // إذا لم يكن أدمن، تسجيل الخروج وإعادة التوجيه مع رسالة خطأ
            Auth::logout();
            return redirect('/login')->with('error', 'لا يمكنك الدخول بهذا المستخدم.');
        }

        // إذا لم يكن مسجلاً، إعادة التوجيه إلى صفحة تسجيل الدخول
        return redirect('/login')->with('error', 'يرجى تسجيل الدخول للوصول.');
    }
}
