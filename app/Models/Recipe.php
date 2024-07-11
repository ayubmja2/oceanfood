<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'instruction', 'ingredients', 'disease_name'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function categories(){
        return $this->belongsToMany(Category::class, 'category_recipe')->withTimestamps();
    }

    public function bookmarkedByUsers(){
        return $this->belongsToMany(User::class, 'recipe_user', 'recipe_id', 'user_id')->withTimestamps();
    }
}
