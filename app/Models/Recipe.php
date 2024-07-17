<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $casts = [
        'ingredients' => 'array',
    ];

    protected $fillable = ['user_id', 'title', 'description', 'instruction', 'ingredients', 'image_url', 'disease_name'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function categories(){
        return $this->belongsToMany(Category::class, 'category_recipe')->withPivot('user_id')->withTimestamps();
    }

    public function bookmarkedByUsers(){
        return $this->belongsToMany(User::class, 'recipe_user')->withTimestamps();
    }
}
