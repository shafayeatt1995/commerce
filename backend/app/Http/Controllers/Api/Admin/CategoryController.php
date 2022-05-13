<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'colum' => 'required'
        ]);
        $categories = Category::where($request->colum, 'LIKE', '%' . $request->keyword . '%')->latest()->paginate(20);
        return response()->json(compact('categories'));
    }

    public function categoryList()
    {
        $this->authorize('admin');
        $categories = Category::orderBy('name')->get();
        return response()->json(compact('categories'));
    }

    public function createCategory(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'name' => 'required|unique:categories',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp',
        ]);

        $slug = Str::slug($request->name);
        $path = 'images/categories/';
        $imageName = $path . $slug . time() . '.webp';

        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        if (Image::make($request->image)->encode('webp', 80)->save($imageName)) {
            $category = new Category();
            $category->name = $request->name;
            $category->slug = $slug;
            $category->image = $imageName;
            $category->save();
        };

        return response()->json('Category successfully created');
    }

    public function updateCategory(Request $request, Category $category)
    {
        $this->authorize('admin');
        $request->validate([
            'name' => 'required',
        ]);

        $slug = Str::slug($request->name);
        $path = 'images/categories/';

        $image = $request->hasFile('image');
        if ($image) {
            if (File::exists($category->image)) {
                unlink($category->image);
            }
            $imageName = $path . $slug . time() . '.webp';
            Image::make($request->image)->encode('webp', 80)->save($imageName);
        } else {
            $imageName = $category->image;
        }

        $category->name = $request->name;
        $category->slug = $slug;
        $category->image = $imageName;
        $category->save();
        return response()->json(["message" => "Category successfully updated"], 200);
    }

    public function deleteCategory(Category $category)
    {
        $this->authorize('admin');
        if (File::exists($category->image)) {
            unlink($category->image);
        }
        $category->delete();
        return response()->json(["message" => "Category successfully deleted"], 200);
    }
}
