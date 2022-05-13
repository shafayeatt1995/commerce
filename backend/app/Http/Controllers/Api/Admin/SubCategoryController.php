<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'colum' => 'required'
        ]);
        $sub_categories = SubCategory::where($request->colum, 'LIKE', '%' . $request->keyword . '%')->with('category')->latest()->paginate(20);
        return response()->json(compact('sub_categories'));
    }

    public function categoryList()
    {
        $this->authorize('admin');
        $categories = SubCategory::orderBy('name')->get();
        return response()->json(compact('categories'));
    }

    public function createCategory(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'name' => 'required',
            'category' => 'required|numeric',
        ]);

        $category = new SubCategory();
        $category->category_id = $request->category;
        $category->name = $request->name;
        $category->slug = Str::slug($request->name) . Str::random(1);
        $category->save();

        return response()->json(["message" => "Sub-Category successfully created"], 200);
    }

    public function updateCategory(Request $request, SubCategory $sub_category)
    {
        $this->authorize('admin');
        $request->validate([
            'name' => 'required',
            'category' => 'required|numeric',
        ]);

        $sub_category->category_id = $request->category;
        $sub_category->name = $request->name;
        $sub_category->slug = Str::slug($request->name) . Str::random(1);
        $sub_category->save();
        return response()->json(["message" => "Sub-Category successfully updated"], 200);
    }

    public function deleteCategory(SubCategory $sub_category)
    {
        $this->authorize('admin');
        $sub_category->delete();
        return response()->json(["message" => "Sub-Category successfully deleted"], 200);
    }
}
