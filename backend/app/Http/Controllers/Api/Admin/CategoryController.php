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
        $categories = Category::where($request->colum, 'LIKE', '%' . $request->keyword . '%')->with('sub_categories', 'image')->latest()->paginate(20);
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
            'image' => 'required|numeric',
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->image = $request->image;
        $category->save();

        return response()->json(["message" => "Category successfully created"], 200);
    }

    public function updateCategory(Request $request, Category $category)
    {
        $this->authorize('admin');
        $request->validate([
            'name' => 'required',
            'image' => 'required|numeric',
        ]);

        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->image = $request->image;
        $category->save();
        return response()->json(["message" => "Category successfully updated"], 200);
    }

    public function deleteCategory(Category $category)
    {
        $this->authorize('admin');
        $category->delete();
        return response()->json(["message" => "Category successfully deleted"], 200);
    }
}
