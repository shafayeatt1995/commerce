<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{

    public function index(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'colum' => 'required'
        ]);
        $materials = Material::where($request->colum, 'LIKE', '%' . $request->keyword . '%')->latest()->paginate(20);
        return response()->json(compact('materials'));
    }

    public function materialList()
    {
        $this->authorize('admin');
        $materials = Material::orderBy('name')->get();
        return response()->json(compact('materials'));
    }

    public function createMaterial(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'name' => 'required',
        ]);

        $material = new Material();
        $material->name = $request->name;
        $material->save();

        return response()->json(["message" => "Material successfully created"], 200);
    }

    public function updateMaterial(Request $request, Material $material)
    {
        $this->authorize('admin');
        $request->validate([
            'name' => 'required',
        ]);

        $material->name = $request->name;
        $material->save();
        return response()->json(["message" => "Material successfully updated"], 200);
    }

    public function deleteMaterial(Material $material)
    {
        $this->authorize('admin');
        $material->delete();
        return response()->json(["message" => "Material successfully deleted"], 200);
    }
}
