<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use App\Notifications\LateFeeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class CategoryController extends Controller
{
    // عرض جميع الفئات
    public function index()
    {
        $categories = Category::with('parent')
        ->orderBy('created_at', 'desc')
        ->get(); // مع العلاقات إذا كنت تحتاجها

        return view('admin.categories', compact('categories'));
    }




    // صفحة إنشاء فئة جديدة
    public function create()
    {
        $categories = Category::whereNull('parent_id')->get(); // جلب الفئات الرئيسية فقط
        return view('admin.categoriesAdd', compact('categories')); // تمرير المتغير إلى الواجهة
    }




    // تخزين فئة جديدة
    public function store(Request $request)
    {
        // التحقق من صحة البيانات المدخلة
        $validated = $request->validate([
            'en_name' => 'required|string|max:255',  // التأكد من الحقل بالإنجليزية
            'ar_name' => 'required|string|max:255',  // التأكد من الحقل بالعربية
            'en_descrieption' => 'required|string',  // التحقق من وصف الفئة بالإنجليزية
            'ar_description' => 'required|string',  // التحقق من وصف الفئة بالعربية
            'parent_id' => 'nullable|exists:categories,id',  // التحقق من الفئة الرئيسية إن كانت موجودة
        ]);

        // DB::beginTransaction();
        // try {

            // تخزين الفئة الجديدة في قاعدة البيانات
            Category::create([
                'en_name' => $validated['en_name'],  // تخزين الاسم بالإنجليزية
                'ar_name' => $validated['ar_name'],  // تخزين الاسم بالعربية
                'en_descrieption' => $validated['en_descrieption'],  // تخزين الوصف بالإنجليزية
                'ar_description' => $validated['ar_description'],  // تخزين الوصف بالعربية
                'parent_id' => $validated['parent_id'] ?? null,  // إذا كانت فئة رئيسية، يتم ربطها
            ]);


            // $text_ar = "تم إرسال اضافة قسم ";
            // $text_en = "Add Category";
            // $title_ar = "قسم جديد";
            // $title_en = "new category";

            // $data = new LateFeeNotification([
            //     'text_en' => $text_en,
            //     'text_ar' => $text_ar,
            //     'title_en' => $title_en,
            //     'title_ar' => $title_ar,
            // ]);

            //$admins = User::role('admin')->get();

             //Notification::send($admins, $data);

            //DB::commit();
            //  إعادة التوجيه مع رسالة نجاح
        return redirect()->route('admin.categories')->with('success', __('web.Category added successfully!'));
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //       // إعادة التوجيه مع رسالة فشل
        // return redirect()->route('admin.categories')->with('error', __('web.Category Faild!'));
        // }

    }





    // صفحة تعديل فئة

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::whereNull('parent_id')->get(); // جلب الفئات الرئيسية فقط
        return view('admin.categoriesEdit', compact('category', 'categories')); // تم إضافة $categories
    }



    // تحديث فئة
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'en_name' => 'required|string|max:255',
            'ar_name' => 'required|string|max:255',
            'en_descrieption' => 'nullable|string',
            'ar_description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $category = Category::findOrFail($id);

        // السماح بتغيير الفئة الرئيسية بغض النظر عن الفروع
        $category->update($data);

        return redirect()->route('admin.categories')->with('success', __('web.Category updated successfully!'));
    }






    // حذف الفئة

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // التحقق من وجود فئات فرعية
        if ($category->children()->count() > 0) {
            return redirect()->route('admin.categories')->with('error', __('web.Cannot delete a category with subcategories.'));
        }

        $category->delete();

        return redirect()->route('admin.categories')->with('success', __('web.Category deleted successfully!'));
    }
}