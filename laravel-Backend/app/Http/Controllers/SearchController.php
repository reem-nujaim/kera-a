<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('query', '');

        if ($query) {
            // البيانات المطلوبة: اسم الصفحة (باللغتين) + رابطها
            $pages = [
                ['name_en' => 'Dashboard', 'name_ar' => 'لوحة التحكم', 'url' => route('dashboard')],
                ['name_en' => 'Home', 'name_ar' => 'الصفحة الرئيسية', 'url' => route('dashboard')],
                ['name_en' => 'Profile', 'name_ar' => 'الملف الشخصي', 'url' => route('profile.show')],
                ['name_en' => 'Edit Profile', 'name_ar' => 'تعديل الملف الشخصي', 'url' => route('profile.edit')],
                ['name_en' => 'Categories', 'name_ar' => 'الفئات', 'url' => route('categories.index')],
                ['name_en' => 'Create Category', 'name_ar' => 'إضافة فئة', 'url' => route('categories.create')],
                ['name_en' => 'Bills', 'name_ar' => 'السندات', 'url' => route('bills.index')],
                ['name_en' => 'Customers', 'name_ar' => 'العملاء', 'url' => route('customers.index')],
                ['name_en' => 'Ratings', 'name_ar' => 'التقييمات', 'url' => route('ratings.index')],
                ['name_en' => 'Settings', 'name_ar' => 'الإعدادات', 'url' => route('setting.index')],
                ['name_en' => 'Settings', 'name_ar' => 'تعديل الإعدادات', 'url' => route('setting.index')],
                ['name_en' => 'Assurances', 'name_ar' => 'التأمينات', 'url' => route('assurances.index')],
                ['name_en' => 'Create Assurances', 'name_ar' => 'إضافة تأمين', 'url' => route('assurances.create')],
                ['name_en' => 'Products', 'name_ar' => 'الأغراض', 'url' => route('items.index')],
                ['name_en' => 'Requests', 'name_ar' => 'الطلبات', 'url' => route('requests.index')],
                ['name_en' => 'Transactions Log', 'name_ar' => 'سجل العمليات', 'url' => route('activity.index')],
                ['name_en' => 'Late Fees', 'name_ar' => 'رسوم المتأخرات', 'url' => route('lateFees.index')],
                ['name_en' => 'Notifications', 'name_ar' => 'الإشعارات', 'url' => route('notifications.index')],
                ['name_en' => 'Reports', 'name_ar' => 'التقارير', 'url' => route('reports.index')],

            ];

            // تصفية البيانات بناءً على النص المدخل (باللغتين)
            $results = array_filter($pages, function ($page) use ($query) {
                return stripos($page['name_en'], $query) !== false || stripos($page['name_ar'], $query) !== false;
            });

            // إعادة النتائج
            return response()->json(array_values($results));
        }

        // إذا لم يتم إدخال أي نص
        return response()->json([]);
    }
}