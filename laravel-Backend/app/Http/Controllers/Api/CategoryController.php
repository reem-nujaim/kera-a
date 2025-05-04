<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // 1. جلب قائمة الفئات
    public function index()
    {
        $categories = Category::all(); // جلب كل الفئات
        return response()->json($categories);
    }

    // 2. إنشاء فئة جديدة
    public function store(Request $request)
    {
        $validated = $request->validate([
            'en_name' => 'required|string|max:255',
            'ar_name' => 'required|string|max:255',
            'en_descrieption' => 'required|string',
            'ar_description' => 'required|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $category = Category::create($validated);

        return response()->json([
            'message' => 'Category created successfully',
            'category' => $category,
        ], 201);
    }

    // 3. عرض فئة واحدة
    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return response()->json($category);
    }

    // 4. تحديث بيانات فئة
    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $validated = $request->validate([
            'en_name' => 'sometimes|string|max:255',
            'ar_name' => 'sometimes|string|max:255',
            'en_descrieption' => 'sometimes|string',
            'ar_description' => 'sometimes|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $category->update($validated);

        return response()->json([
            'message' => 'Category updated successfully',
            'category' => $category,
        ]);
    }

    // 5. حذف فئة
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}

