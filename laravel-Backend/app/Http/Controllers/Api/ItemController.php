<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Notifications\AddItemNotification;
use Notification;

class ItemController extends Controller
{
    public function index()
{
    $items = Item::all(); // جلب جميع العناصر
    return response()->json($items); // إرسالها كاستجابة JSON
}
// 1. Create Item

public function store(Request $request)
{
    // تحقق من صحة البيانات
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'images' => 'required|json',
        'status' => 'required|in:excellent,good,acceptable,barely used',
        'available' => 'required|boolean',
        'admin_approval' => 'boolean',
        'location' => 'required|string|max:255',
        'price_assurance' => 'required|numeric|min:0',
        'delivery_method' => 'required|in:self,courier',
        'price_per_hour' => 'nullable|numeric|min:0',
        'price_per_day' => 'nullable|numeric|min:0',
        'quantity' => 'required|integer|min:1',
        'min_rental_duration' => 'nullable|integer|min:0',
        'max_rental_duration' => 'nullable|integer|min:0',
        'availability_hours' => 'nullable|integer|min:0',
        'user_id' => 'required|exists:users,id',
        'category_id' => 'required|exists:categories,id',
    ]);
    

        // إنشاء السجل في قاعدة البيانات
        $item = Item::create($validated);

        // إرسال الإشعار إلى الأدمن فقط
        $admin = User::role('admin')->get();
        Notification::send($admin, new AddItemNotification($item));

       
        // إرجاع الرد مع بيانات الغرض الذي تم إضافته
        return response()->json(['message' => 'تمت إضافة الغرض بنجاح.', 'data' => $item], 201);

}
//GET Single Item
public function show($id)
{
    $item = Item::find($id); // البحث عن العنصر بواسطة المعرف

    if (!$item) {
        return response()->json(['message' => 'Item not found'], 404);
    }

    return response()->json($item); // إرسال العنصر كاستجابة
}
//Update Item
public function update(Request $request, $id)
{
    // جلب العنصر الموجود
    $item = Item::findOrFail($id);

    // تحقق من صحة البيانات
    $validated = $request->validate([
        'name' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'images' => 'nullable|json',
        'status' => 'nullable|in:excellent,good,acceptable,barely used',
        'available' => 'nullable|boolean',
        'admin_approval' => 'nullable|boolean',
        'location' => 'nullable|string|max:255',
        'price_assurance' => 'nullable|numeric|min:0',
        'delivery_method' => 'nullable|in:self,courier',
        'price_per_hour' => 'nullable|numeric|min:0',
        'price_per_day' => 'nullable|numeric|min:0',
        'quantity' => 'nullable|integer|min:1',
        'min_rental_duration' => 'nullable|integer|min:0',
        'max_rental_duration' => 'nullable|integer|min:0',
        'availability_hours' => 'nullable|integer|min:0',
        'user_id' => 'nullable|exists:users,id',
        'category_id' => 'nullable|exists:categories,id',
    ]);

    // تحديث السجل في قاعدة البيانات
    $item->update($validated);

    // إرجاع استجابة
    return response()->json([
        'message' => 'Item updated successfully',
        'item' => $item,
    ], 200);
}

// Delete Item
public function destroy($id)
{
    $item = Item::find($id); // البحث عن العنصر بواسطة المعرف

    if (!$item) {
        return response()->json(['message' => 'Item not found'], 404);
    }

    $item->delete(); // حذف العنصر

    return response()->json(['message' => 'Item deleted successfully']);
}

public function getItemsByCategory($categoryId)
{
    // جلب العناصر بناءً على الـ category_id
    $items = Item::where('category_id', $categoryId)->get();

    // التحقق مما إذا كانت الفئة تحتوي على عناصر أم لا
    if ($items->isEmpty()) {
        return response()->json(['message' => 'No items found for this category.'], 404);
    }

    return response()->json(['data' => $items], 200);
}

public function getItemsByUser($userId)
{
    // جلب العناصر بناءً على user_id
    $items = Item::where('user_id', $userId)->get();

    // التحقق مما إذا كان المستخدم قد أضاف عناصر أم لا
    if ($items->isEmpty()) {
        return response()->json(['message' => 'No items found for this user.'], 404);
    }

    return response()->json(['data' => $items], 200);
}

public function searchItems(Request $request)
{
    $validated = $request->validate([
        'query' => 'required|string', // التحقق من صحة مدخل البحث
    ]);

    // البحث في جدول الأغراض بالاعتماد على الاسم
    $items = Item::where('name', 'LIKE', '%' . $validated['query'] . '%')->get();

    // التحقق إذا لم يتم العثور على أي نتائج
    if ($items->isEmpty()) {
        return response()->json(['message' => 'لم يتم العثور على أي أغراض مطابقة.'], 404);
    }

    return response()->json([
        'message' => 'تم العثور على الأغراض بنجاح.',
        'data' => $items
    ]);
}








}


