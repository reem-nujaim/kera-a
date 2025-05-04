<?php

namespace App\Http\Controllers;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
{
    // جلب جميع الأنشطة
    $query = Activity::query();

    // تطبيق الفلترة بناءً على اسم النشاط (log_name)
    if ($request->filled('log_name')) {
        $query->where('log_name', $request->log_name);
    }

    // تطبيق الفلترة بناءً على نوع الحدث (event)
    if ($request->filled('event')) {
        $query->where('event', $request->event);
    }

    // تطبيق الفلترة بناءً على المستخدم
    if ($request->filled('causer_id')) {
        $query->where('causer_id', $request->causer_id);
    }

    // تطبيق الفلترة بناءً على التاريخ
    if ($request->filled('date')) {
        $query->whereDate('created_at', $request->date);
    }

    // ترتيب السجلات وعرضها
    $activities = $query->whereIn('log_name', [
        'categories',
        'bills',
        'assurances',
        'lateFees',
        'setting',
        'ratings',
        'users',
    ])->latest()->get();

    return view('Admin.log', compact('activities'));
}

}
