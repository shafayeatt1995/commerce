<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'colum' => 'required'
        ]);
        $sizes = Size::where($request->colum, 'LIKE', '%' . $request->keyword . '%')->latest()->paginate(20);
        return response()->json(compact('sizes'));
    }

    public function sizeList()
    {
        $this->authorize('admin');
        $sizes = Size::orderBy('name')->get();
        return response()->json(compact('sizes'));
    }

    public function createSize(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'name' => 'required',
        ]);

        $size = new Size();
        $size->name = $request->name;
        $size->save();

        return response()->json(["message" => "Size successfully created"], 200);
    }

    public function updateSize(Request $request, Size $size)
    {
        $this->authorize('admin');
        $request->validate([
            'name' => 'required',
        ]);

        $size->name = $request->name;
        $size->save();
        return response()->json(["message" => "Size successfully updated"], 200);
    }

    public function deleteSize(Size $size)
    {
        $this->authorize('admin');
        $size->delete();
        return response()->json(["message" => "Size successfully deleted"], 200);
    }
}
