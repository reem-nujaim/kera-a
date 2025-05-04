<?php

namespace App\Http\Controllers;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function index()
    {
        // استرجاع جميع التقييمات
        $ratings = Rating::with(['user', 'item'])
        ->orderBy('created_at', 'desc')
        ->get(); // جلب التقييمات مع بيانات المستخدم والمنتج

        // تمرير التقييمات إلى الـ View
        return view('admin.ratings', compact('ratings'));
    }









    public function delete($id)
    {
        // حذف التقييم
        $rating = Rating::find($id);
        if ($rating) {
            $rating->delete();
            return redirect()->route('ratings.index')->with('success', __('web.Rating deleted successfully!'));
        }

        return redirect()->route('ratings.index')->with('error', __('web.Rating not found.'));
    }


    public function hide($id)
    {
        // إخفاء أو إظهار التقييم
        $rating = Rating::find($id);
        if ($rating) {
            $rating->hidden = !$rating->hidden; // تغيير الحالة
            $rating->save();
            $message = $rating->hidden
                ? __('web.Rating hidden successfully!')
                : __('web.Rating unhidden successfully!');
            return redirect()->route('ratings.index')->with('success', $message);
        }

        return redirect()->route('ratings.index')->with('error', __('web.Rating not found.'));
    }
}