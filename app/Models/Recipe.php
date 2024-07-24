<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;


    protected $fillable = ['user_id', 'title', 'description', 'instruction', 'image_url', 'disease_name'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function ingredients(){
        return $this->belongsToMany(Ingredient::class, 'ingredient_recipe')->withPivot('quantity', 'unit');
    }
    public function categories(){
        return $this->belongsToMany(Category::class, 'category_recipe')->withPivot('user_id')->withTimestamps();
    }

    public function bookmarkedByUsers(){
        return $this->belongsToMany(User::class, 'recipe_user')->withTimestamps();
    }
}
