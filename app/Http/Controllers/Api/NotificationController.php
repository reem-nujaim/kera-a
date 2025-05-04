<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Item;
use App\Models\RentalRequest;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('sanctum')->user();

        

        if (!$user) {
            return response()->json(['message' => 'المستخدم غير موجود.'], 404);
        }

        $userType = $this->getUserType($user);

        if ($userType === 'landlord') {
            $notifications = DatabaseNotification::whereHasMorph('notifiable', [User::class], function ($query) use ($user) {
                $query->whereIn('id', Item::where('user_id', $user->id)->pluck('user_id'));
            })->get();
        } elseif ($userType === 'tenant') {
            $notifications = DatabaseNotification::whereHasMorph('notifiable', [User::class], function ($query) use ($user) {
                $query->whereIn('id', RentalRequest::where('user_id', $user->id)->pluck('user_id'));
            })->get();
        } else {
            return response()->json(['message' => 'لا توجد إشعارات.'], 404);
        }

        return response()->json([
            'message' => 'تم جلب الإشعارات بنجاح.',
            'data' => $notifications
        ]);
    }

    public function unread(Request $request)
    {
      $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json(['message' => 'المستخدم غير موجود.'], 404);
        }

        $userType = $this->getUserType($user);

        if ($userType === 'landlord') {
            $unreadNotifications = DatabaseNotification::whereHasMorph('notifiable', [User::class], function ($query) use ($user) {
                $query->whereIn('id', Item::where('user_id', $user->id)->pluck('user_id'));
            })->whereNull('read_at')->get();
        } elseif ($userType === 'tenant') {
            $unreadNotifications = DatabaseNotification::whereHasMorph('notifiable', [User::class], function ($query) use ($user) {
                $query->whereIn('id', RentalRequest::where('user_id', $user->id)->pluck('user_id'));
            })->whereNull('read_at')->get();
        } else {
            return response()->json(['message' => 'لا توجد إشعارات غير مقروءة.'], 404);
        }

        return response()->json([
            'message' => 'تم جلب الإشعارات غير المقروءة بنجاح.',
            'data' => $unreadNotifications
        ]);
    }

    public function markAsRead(Request $request, $id)
    {
        $notification = DatabaseNotification::find($id);

        if (!$notification) {
            return response()->json(['message' => 'الإشعار غير موجود.'], 404);
        }

        $notification->markAsRead();

        return response()->json(['message' => 'تم تحديد الإشعار كمقروء بنجاح.']);
    }

    public function markAllAsRead(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json(['message' => 'المستخدم غير موجود.'], 404);
        }

        $user->unreadNotifications->markAsRead();

        return response()->json(['message' => 'تم تحديد جميع الإشعارات كمقروءة بنجاح.']);
    }

    public function destroy(Request $request, $id)
    {
        $notification = DatabaseNotification::find($id);

        if (!$notification) {
            return response()->json(['message' => 'الإشعار غير موجود.'], 404);
        }

        $notification->delete();

        return response()->json(['message' => 'تم حذف الإشعار بنجاح.']);
    }

    public function destroyAll(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json(['message' => 'المستخدم غير موجود.'], 404);
        }

        $user->notifications()->delete();

        return response()->json(['message' => 'تم حذف جميع الإشعارات بنجاح.']);
    }

    private function getUserType($user)
    {
        if (Item::where('user_id', $user->id)->exists()) {
            return 'landlord';
        }

        if (RentalRequest::where('user_id', $user->id)->exists()) {
            return 'tenant';
        }

        return null;
    }

    public function getUserNotifications(Request $request)
    {
        $user = Auth::user(); // جلب المستخدم الحالي
        $notifications = $user->notifications; // جلب إشعارات المستخدم

        return response()->json([
            'notifications' => $notifications
        ]);
    }

    public function getUnreadCount(Request $request)
{
    $user = auth('sanctum')->user();

    if (!$user) {
        return response()->json(['message' => 'المستخدم غير موجود.'], 404);
    }

    $userType = $this->getUserType($user);

    if ($userType === 'landlord') {
        $count = DatabaseNotification::whereHasMorph('notifiable', [User::class], function ($query) use ($user) {
            $query->whereIn('id', Item::where('user_id', $user->id)->pluck('user_id'));
        })->whereNull('read_at')->count();
    } elseif ($userType === 'tenant') {
        $count = DatabaseNotification::whereHasMorph('notifiable', [User::class], function ($query) use ($user) {
            $query->whereIn('id', RentalRequest::where('user_id', $user->id)->pluck('user_id'));
        })->whereNull('read_at')->count();
    } else {
        return response()->json(['message' => 'لا توجد إشعارات غير مقروءة.'], 404);
    }

    return response()->json([
        'message' => 'تم جلب عدد الإشعارات غير المقروءة بنجاح.',
        'count' => $count
    ]);
}

}