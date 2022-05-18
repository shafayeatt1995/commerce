<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class PhotoController extends Controller
{

    public function index(Request $request)
    {
        $this->authorize("admin");
        $request->validate([
            'sort' => 'required'
        ]);

        if ($request->sort === 'new') {
            $photos = Photo::where('name', 'LIKE', '%' . $request->keyword . '%')->latest()->paginate(20);
        } else if ($request->sort === 'old') {
            $photos = Photo::where('name', 'LIKE', '%' . $request->keyword . '%')->paginate(20);
        } else if ($request->sort === 'small') {
            $photos = Photo::where('name', 'LIKE', '%' . $request->keyword . '%')->orderBy('size')->paginate(20);
        } else if ($request->sort === 'large') {
            $photos = Photo::where('name', 'LIKE', '%' . $request->keyword . '%')->orderBy('size', 'desc')->paginate(20);
        }
        return response()->json(compact("photos"));
    }

    public function uploadPhoto(Request $request)
    {
        $this->authorize("admin");
        $request->validate([
            'name' => 'required',
            'size' => 'required|numeric',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp',
        ]);

        $slug = Str::slug(Str::substr($request->name, 0, 20));
        $path = 'images/general/';
        $imageName = $path . $slug . time() . '.webp';

        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        if (Image::make($request->photo)->encode('webp', 80)->save($imageName)) {
            $photo = new Photo();
            $photo->user_id = Auth::id();
            $photo->name = $request->name;
            $photo->photo = $imageName;
            $photo->size = $request->size;
            $photo->save();
        };
    }

    public function deletePhoto(Photo $photo)
    {
        if (File::exists($photo->photo)) {
            unlink($photo->photo);
        }
        $photo->delete();
        return response()->json(["message" => "Image successfully deleted"], 200);
    }
}
