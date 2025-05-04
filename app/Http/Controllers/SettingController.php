<?php
namespace App\Http\Controllers;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    // عرض صفحة الإعدادات
    public function index()
    {
        $setting = Setting::first(); // الحصول على أول سجل من جدول الإعدادات
        return view('Admin.setting', compact('setting'));
    }





    // عرض صفحة تعديل الإعدادات
    public function edit($id)
    {
        $setting = Setting::findOrFail($id);
        return view('Admin.settingEdit', compact('setting'));


    }

    // تحديث الإعدادات
    public function update(Request $request, $id)
    {
        $setting = Setting::findOrFail($id);

        // التحقق من صحة المدخلات
        $data = $request->validate([
            'logo' => 'nullable|image|max:1024',
            'en_about_us' => 'nullable|string|max:1000',
            'ar_about_us' => 'nullable|string|max:1000',
            'en_privacy_policy' => 'nullable|string|max:1000',
            'ar_privacy_policy' => 'nullable|string|max:1000',
            'app_budget' => 'nullable|numeric|min:0',
        ]);

        // التعامل مع الشعار إذا كان قد تم رفعه
        if ($request->hasFile('logo')) {
            // حذف الشعار القديم إذا كان موجودًا
            if ($setting->logo) {
                Storage::delete($setting->logo);
            }
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        // تحديث البيانات في قاعدة البيانات
        $setting->update($data);

        // إعادة توجيه المستخدم إلى صفحة الإعدادات مع رسالة النجاح
        return redirect()->route('setting.index')->with('success', __('web.Settings updated successfully.'));
    }
}
