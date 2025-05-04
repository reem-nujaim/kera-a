<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\RentalRequest;
use App\Models\Category;
use App\Models\Assurance;
use App\Models\Rating;
use App\Models\LateFee;
use App\Models\Bill;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // جلب عدد السجلات من كل جدول
        $data = [
            'customers' => User::where('is_admin', false)->count(), // عدد المستخدمين باستثناء الأدمن
            'products' => Item::count(),
            'requests' => RentalRequest::count(),
            'categories' => Category::count(),
            'assurances' => Assurance::count(),
            'ratings' => Rating::count(),
            'lateFees' => LateFee::count(),
            'bills' => Bill::count(),
        ];

        // إرسال البيانات إلى الواجهة
        return view('Admin.index', compact('data'));
    }
    /**
     * جلب إحصائيات المستخدمين لكل شهر.
     */
    public function getCustomersStats()
    {
        // جلب عدد المستخدمين لكل شهر من السنة الحالية
        $customersByMonth = User::where('is_admin', false)
            ->selectRaw('MONTH(created_at) as month, COUNT(id) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        // إضافة الأشهر المفقودة بقيمة صفر
        $allMonths = range(1, 12);
        $customersByMonth = array_replace(array_fill_keys($allMonths, 0), $customersByMonth);

        return response()->json([
            'status' => 'success',
            'year' => now()->year,
            'data' => $customersByMonth
        ]);
    }

    public function getMostOrderedItems()
    {
        $mostOrderedItems = DB::table('items')
        ->select('items.id', 'items.name', DB::raw('COUNT(rental_requests.item_id) as order_count'))
        ->join('rental_requests', 'items.id', '=', 'rental_requests.item_id') // افترض أن هناك جدولًا للطلبات
        ->groupBy('items.id', 'items.name') // تجميع حسب المنتج
        ->orderBy('order_count', 'desc') // ترتيب حسب عدد الطلبات
        ->take(5) // جلب 5 منتجات الأكثر طلبًا
        ->get();

    return response()->json($mostOrderedItems);
    }
}

