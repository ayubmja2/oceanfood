<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecipeBookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $bookmarkedRecipes = $user->bookmarkedRecipes()->with('user')->get();
        $categories = $user->categories;
        $uncategorizedRecipes = $user->bookmarkedRecipes()->whereDoesntHave('categories')->get();
        return view('recipeBook.index', compact('bookmarkedRecipes', 'categories', 'uncategorizedRecipes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $recipebook = Recipe::with('user')->findOrFail($id);
        return view('recipeBook.show', compact('recipebook'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function toggleBookmark(Request $request, $id){
        $recipe = Recipe::findOrFail($id);
        $user = Auth::user();

        if($user->bookmarkedRecipes()->where('recipe_id',$id)->exists()){
            $user->bookmarkedRecipes()->detach($recipe);
            return response()->json(['bookmark' => false]);
        }else {
            $user->bookmarkedRecipes()->attach($recipe);
            return response()->json(['bookmark' => true]);
        }
    }
    public function storeCategory(Request $request){
        $request->validate([
            'title' => 'required|string',
        ]);

        $category = new Category();
        $category->title = $request->title;
        $category->user_id = Auth::id();
        $category->save();

        return redirect()->route('recipebook.index');
    }

    public function assignRecipe(Request $request, Category $category){
        $request = Recipe::findOrFail($request->recipe_id);
        $category->recipes()->attach($request);

        return response()->json(['status' => 'success']);
    }

}
