<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecipeBookController extends Controller
{
    public function bookmark($id){
        $recipe = Recipe::findOrFail($id);
        Auth::user()->bookmarkedRecipes()->attach($recipe);

        return redirect()->back()-with('success', 'Recipe bookmarked successfully!');
    }

    public function unbookmark($id){
        $recipe = Recipe::findOrFail($id);
        Auth::user()->bookmarkedRecipes()->detach($recipe);

        return redirect()->back()->with('success', 'Recipe bookmarked successfully!');
    }



    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('recipeBook.index');
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
    public function show(string $id)
    {
        //
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
}
