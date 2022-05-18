<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image'];

    public function image()
    {
        return $this->belongsTo(Photo::class, 'image');
    }

    public function sub_categories()
    {
        return $this->hasMany(SubCategory::class);
    }
}
