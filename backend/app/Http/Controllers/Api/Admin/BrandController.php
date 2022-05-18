<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'colum' => 'required'
        ]);
        $brands = Brand::where($request->colum, 'LIKE', '%' . $request->keyword . '%')->with('logo')->latest()->paginate(20);
        return response()->json(compact('brands'));
    }

    public function brandList()
    {
        $this->authorize('admin');
        $brands = Brand::orderBy('name')->get();
        return response()->json(compact('brands'));
    }

    public function createBrand(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'name' => 'required|unique:brands',
            'logo' => 'required|numeric',
        ]);

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        $brand->logo = $request->logo;
        $brand->save();
        return response()->json(["message" => "Brand successfully created"], 200);
    }

    public function updateBrand(Request $request, Brand $brand)
    {
        $this->authorize('admin');
        $request->validate([
            'name' => 'required',
            'logo' => 'required|numeric',
        ]);

        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        $brand->logo = $request->logo;
        $brand->save();
        return response()->json(["message" => "Brand successfully updated"], 200);
    }

    public function deleteBrand(Brand $brand)
    {
        $this->authorize('admin');
        $brand->delete();
        return response()->json(["message" => "Brand successfully deleted"], 200);
    }
}
