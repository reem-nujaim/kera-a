<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Str;


class NotificationController extends Controller
{
    public function index()
    {
        // جلب جميع الإشعارات للمستخدم الحالي
    $locale = app()->getLocale();  // جلب اللغة الحالية

    // جلب جميع الإشعارات للمستخدم الحالي مع تقسيمها إلى صفحات
    $notifications = Auth::user()->notifications()->latest()->paginate(10); // 10 إشعارات لكل صفحة

    if ($notifications->isEmpty()) {
        return response()->json([
            'message' => __('web.No notifications available'),
            'totalCount' => 0,
            'notifications' => [],
        ]);
    }

    // ترجمة النصوص والعناوين حسب اللغة
    $notifications->getCollection()->transform(function ($notification) use ($locale) {
        $data = $notification->data;

        $notification->message = $data['message_' . $locale] ?? $data['message_en'];
        $notification->title = $data['title_' . $locale] ?? $data['title_en'];
        $notification->is_read = $notification->read_at ? true : false;

        return $notification;
    });

    return view('Admin.notifications', compact('notifications'));
    }
    // جلب الإشعارات
    public function show()
{
    $locale = app()->getLocale();  // جلب اللغة الحالية للمتصفح

    $notifications = Auth::user()->unreadNotifications->sortByDesc('created_at')->take(5); // فقط الإشعارات غير المقروءة
    if ($notifications->isEmpty()) {
        return response()->json([
            'message' => __('web.No unread notifications'),
            'unreadCount' => 0,
            'notifications' => [],
        ]);
    }

    // جلب الإشعارات بالنصوص المناسبة للغة
    $notifications = $notifications->map(function ($notification) use ($locale) {
        $data = $notification->data;  // جلب البيانات من الحقل 'data'

        // اختيار النص والعنوان حسب اللغة الحالية
        $notification->message = $data['message_' . $locale] ?? $data['message_en'];  // اختيار النص
        $notification->title = $data['title_' . $locale] ?? $data['title_en'];  // اختيار العنوان

        return $notification;
    });

    return response()->json([
        'notifications' => $notifications,
        'unreadCount' => $notifications->count(),
    ]);
}

    // تعيين إشعار كمقروء
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications->find($id);

        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => __('web.Notification not found')], 404);
    }
    public function markAllAsRead()
{
    $user = Auth::user();

    if ($user->unreadNotifications->isNotEmpty()) {
        $user->unreadNotifications->markAsRead();
    }

    return $this->index();
}

// إعادة التوجيه إلى رابط الإشعار
public function redirectToNotification($id)
{
    $notification = Auth::user()->notifications->find($id);

    if ($notification) {
        $notification->markAsRead(); // وضع الإشعار كمقروء

        $url = $notification->data['url'] ?? route('notifications.index');

        // التأكد من أن الرابط يتوافق مع اللغة المختارة
        $localizedUrl = LaravelLocalization::getLocalizedURL(app()->getLocale(), $url);

        return redirect($localizedUrl);
    }

    return redirect()->route('notifications.index')->with('error', __('web.Notification not found'));
}




}
