<?php
namespace App\Http\Controllers;
use App\Models\Item;
use Illuminate\Http\Request;
use Spinen\Geometry\Geometry;
use Illuminate\Support\Facades\DB;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait; // تأكد من استيراد الحزمة الصحيحة


class ItemController extends Controller
{
    // Display all Item
    public function index()
    {


        $items = Item::orderBy('created_at', 'desc')->get(); // ترتيب العناصر من الأحدث إلى الأقدم
        return view('Admin.items', compact('items'));


    }


    // Delete a Item
    public function destroy($id)
    {
        $items = Item::findOrFail($id);
        $items->delete();

        return redirect()->route('items.index')->with('success', __('web.Product deleted successfully.'));
    }




    public function show($id)
    {
        $item = item::find($id);

    if (!$item) {
        return abort(404, 'Request not found');
    }


        return view('Admin.show', compact('item'));
    }





    // Route for approving the item
    public function approve(Item $item)
    {
        $item->admin_approval = true;
        $item->save();
        return response()->json(['success' => true]);
    }


    // Route for rejecting the item
    public function reject(Item $item)
    {
        $item->admin_approval = false;
        $item->save();
        return response()->json(['success' => true]);
    }





    public function hide($id)
    {
        //تتغير حالة العنصر
        $item = Item::find($id);
        if ($item) {
            $item->available = !$item->available; // تغيير الحالة
            $item->save();
            $message = $item->available
                ? __('web.Availability status changed to Available.')
                : __('web.Availability status changed to Not Available.');
            return redirect()->route('items.index')->with('success', $message);
        }

        return redirect()->route('items.index')->with('error', __('web.Item not found.'));
    }






    public function fixImages()
    {
        $items = DB::table('items')->get();

        foreach ($items as $item) {
            $images = $item->images;

            // فك التشفير إذا كانت السلسلة تحتوي على JSON مشفر مرتين
            if (is_string($images) && str_starts_with($images, '[\\"')) {
                $decodedImages = json_decode($images, true);
                DB::table('items')
                    ->where('id', $item->id)
                    ->update(['images' => json_encode($decodedImages)]);
            }
        }

        return "Images column fixed successfully!";
    }

}