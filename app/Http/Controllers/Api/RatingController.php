<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Rating;


class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Rating::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'score' => 'required|numeric|min:0|max:5',
            'item_id' => 'required|exists:items,id',
            'user_id' => 'required|exists:users,id',
        ]);
    
        $rating = Rating::create($request->all());
    
        return response()->json(['message' => 'تم إنشاء التقييم بنجاح', 'rating' => $rating], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rating = Rating::find($id);

        if (!$rating) {
            return response()->json([
                'message' => 'Rating not found',
            ], 404);
        }
    
        return response()->json($rating);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rating = Rating::findOrFail($id);

        $request->validate([
            'score' => 'nullable|numeric|min:0|max:5',
            'comment' => 'nullable|string',
            'hidden' => 'nullable|boolean'
        ]);
    
        $rating->update($request->all());
    
        return response()->json(['message' => 'تم تحديث التقييم بنجاح', 'rating' => $rating]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rating = Rating::findOrFail($id);
    $rating->delete();

    return response()->json(['message' => 'تم حذف التقييم بنجاح']);
    }
}
