<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function show($category){
        $category = Category::with('user')->findOrFail($category);
//        dd($category->title);

        return view('category.show', compact('category'));
    }

    public function assignRecipe(Request $request, Category $category){
        $recipe = Recipe::findOrFail($request->recipe_id);
        $category->recipes()->attach($recipe);

        return response()->json(['status' => 'success']);
    }

    public function delete(Request $request){
        $categoryId = $request->input('category_id');
        $userId = Auth::id();

        $category = Category::where('id', $categoryId)->where('user_id', $userId)->first();

        if($category){

            DB::table('category_recipe')->where('category_id', $categoryId)->where('user_id', $userId)->delete();
            $category->delete();

            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 400);
    }

    public function rename(Request $request) {
        $categoryId = $request->input('category_id');
        $newTitle = $request->input('title');
        $userid = Auth::id();

        $category = Category::where('id', $categoryId)->where('user_id', $userid)->first();

        if($category) {
            $category->title = $newTitle;
            $category->save();

            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 400);
    }
}
