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
        $brands = Brand::where($request->colum, 'LIKE', '%' . $request->keyword . '%')->latest()->paginate(20);
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
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp',
        ]);

        $slug = Str::slug($request->name);
        $path = 'images/brands/';
        $imageName = $path . $slug . time() . '.webp';

        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        if (Image::make($request->logo)->encode('webp', 80)->save($imageName)) {
            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = $slug;
            $brand->logo = $imageName;
            $brand->save();
        };

        return response()->json(["message" => "Brand successfully created"], 200);
    }

    public function updateBrand(Request $request, Brand $brand)
    {
        $this->authorize('admin');
        $request->validate([
            'name' => 'required',
        ]);

        $slug = Str::slug($request->name);
        $path = 'images/brands/';

        $image = $request->hasFile('logo');
        if ($image) {
            if (File::exists($brand->logo)) {
                unlink($brand->logo);
            }
            $imageName = $path . $slug . time() . '.webp';
            Image::make($request->logo)->encode('webp', 80)->save($imageName);
        } else {
            $imageName = $brand->logo;
        }

        $brand->name = $request->name;
        $brand->slug = $slug;
        $brand->logo = $imageName;
        $brand->save();
        return response()->json(["message" => "Brand successfully updated"], 200);
    }

    public function deleteBrand(Brand $brand)
    {
        $this->authorize('admin');
        if (File::exists($brand->logo)) {
            unlink($brand->logo);
        }
        $brand->delete();
        return response()->json(["message" => "Brand successfully deleted"], 200);
    }
}
