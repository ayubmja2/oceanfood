<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index(){
        $user = Auth::user();
        $categories = $user->categories;
        $uncategorizedRecipes = $user->bookmarkedRecipes()->doesntHave('categories')->get();

        return view('category.index', compact('categories', 'uncategorizedRecipes'));
    }

    public function store(Request $request){
        $request->validate([
            'title' => 'required',
        ]);

        $category = new Category();
        $category->title = $request->title;
        $category->user_id = Auth::id();
        $category->save();

        return redirect()->route('categories.index');
    }

    public function assignRecipe(Request $request, Category $category){
        $recipe = Recipe::findOrFail($request->recipe_id);
        $category->recipes()->attach($recipe);

        return response()->json(['status' => 'success']);
    }
}
