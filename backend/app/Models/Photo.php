<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function brand()
    {
        return $this->hasOne(Brand::class);
    }

    public function category()
    {
        return $this->hasOne(Category::class);
    }
}
