<?php

namespace App\Http\Controllers;

use App\Events\RecipeCreated;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Recipe;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $recipes = Cache::remember('recipes_page_' . request('page', 1), 60, function () {
           return  Recipe::with('user')->orderBy('created_at', 'desc')->paginate(10);
        });
        $user = Auth::user();

        $collections = Cache::remember('user_categories_' . $user->id, 60, function () use ($user){
            return $user->categories;
        });
        return view('home.index', compact('recipes', 'collections'));
    }

    public function latest() {
        $recipes = Recipe::latest()->take(10)->get();
        $recipes->each(function ($recipe){
            $recipe->component_html = view('components.recipe-card', ['recipe' => $recipe])->render();
        });
        return response()->json($recipes);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('recipe.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'instruction' => 'required|string',
            'ingredients' => 'required|array',
            'ingredients.*.name' => 'required|string',
            'ingredients.*.quantity' => 'required|string',
            'ingredients.*.unit' => 'required|string',
            'disease_name' => 'required|string',
            'image' => 'required|image|max:10240',
        ]);

        //Handle the file upload
        if ($request->hasFile('image')) {
            $user = $request->user();
            $image = $request->file('image');

            // Initialize ImageManager with the driver
            $manager = new ImageManager(new Driver());

            // Resize the image
            $resizedImage = $manager->read($image->getPathname())->resize(200, 200);

            //Encode the image
            $encodedImage = $resizedImage->toJpg(75);

            //create temporary file path
            $tempPath = sys_get_temp_dir() . '/' . $image->hashName() . '.jpg';

            //save the encoded image to the temp path
            file_put_contents($tempPath, $encodedImage);

            $path = Storage::disk('spaces')->putFileAs("images/user_uploads/user_{$user->id}/food_images", new File($tempPath), $image->hashName()
                . '.jpg', 'public');

            $imageUrl = Storage::disk('spaces')->url($path);

            // Delete the temporary file
            unlink($tempPath);

        }


       $recipe = $request->user()->recipes()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'instruction' => $validated['instruction'],
            'disease_name' => $validated['disease_name'],
            'image_url' => $imageUrl ?? null,
        ]);

        // Attach ingredients to the recipe

        foreach ($validated['ingredients'] as $ingredientData) {
            $ingredient = Ingredient::firstOrCreate(['name' => $ingredientData['name']]);

            // Convert fraction to decimal
            $quantity = $ingredientData['quantity'];
            if (strpos($quantity, '/') !== false) {
                $parts = explode('/', $quantity);
                if (count($parts) == 2) {
                    $quantity = floatval($parts[0]) / floatval($parts[1]);
                }
            } else {
                $quantity = floatval($quantity);
            }

            $recipe->ingredients()->attach($ingredient->id, [
                'quantity' => $quantity,
                'unit' => $ingredientData['unit']
            ]);
        }

        RecipeCreated::dispatch($recipe);

        return response()->json(['success' => true]);
    }

    /**
     * Display the specified resource.
     */
    public function show($recipeId)
    {
        $recipe = Recipe::with('ingredients')->findOrFail($recipeId);

        $userId = Auth::id();
        $isInCategory = DB::table('category_recipe')->where('recipe_id', $recipeId)->where('user_id', $userId)->exists();

        $user = Auth::user();
        $userAllergens = $user->allergens ?? [];
        return view('recipe.show', compact('recipe', 'isInCategory', 'userAllergens'));
    }

    public function removeFromCategory(Request $request){
        $userId = Auth::id();
        $recipeId = $request->input('recipe_id');

        $exists = DB::table('category_recipe')->where('recipe_id',$recipeId)->where('user_id',$userId)->exists();

        if($exists) {
            DB::table('category_recipe')->where('recipe_id', $recipeId)->where('user_id', $userId)->delete();

            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 400);
    }

    public function loadMore(Request $request) {
        $page = $request->input('page', 1);
        $recipes = Recipe::orderBy('created_at', 'desc')->paginate(10, ['*'], 'page', $page);

        $html = view('partials.recipe-cards', compact('recipes'))->render();
        return response()->json(['html' => $html]);
    }

    public function checkNew(Request $request){
        $lastRecipeId = $request->input('last_recipe_id');
        $newRecipes = Recipe::where('id', '>', $lastRecipeId)->orderBy('created_at', 'desc')->get();

        if($newRecipes->isNotEmpty()) {
            return response()->json([
                'new_recipes' => true,
                'recipes' => $newRecipes,
                'last_recipe_id' => $newRecipes->last()->id
            ]);
        }
        return response()->json(['new_recipes' => false]);
    }

    public function search(Request $request){

        $keyword = $request->input('keyword');

        $recipes = Recipe::when($keyword, function ($query, $keyword) {
            return $query->where('title', 'LIKE', '%' . $keyword . '%');
        })->orderBy('created_at', 'desc')->paginate(5);

        if($recipes->isEmpty()){
            return response()->json(['error' => 'No recipes found.'], 404);
        }

        $html = '';

        foreach($recipes as $recipe){
            $html .= view('components.recipe-card',compact('recipe'))->render();
        }


        return response()->json(['html' => $html], 200);
    }

    public function fetchNewRecipes(Request $request)
    {
        $latestRecipeId = $request->input('latest_recipe_id', 0);
        $newRecipes = Recipe::where('id', '>', $latestRecipeId)->orderBy('created_at', 'desc')->get();

        return response()->json($newRecipes);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($recipeId)
    {
        $recipe = Recipe::findOrFail($recipeId);
        if ($recipe->user_id !== Auth::id()){
            abort(403, 'Unauthorized action.');
        }
        return view('recipe.edit', compact('recipe'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $recipeId)
    {
        $recipe = Recipe::findOrFail($recipeId);

        if ($recipe->user_id !== Auth::id()){
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'instruction' => 'required|string',
            'ingredients' => 'required|array',
            'ingredients.*.name' => 'required|string',
            'ingredients.*.quantity' => 'required|string',
            'ingredients.*.unit' => 'required|string',
            'disease_name' => 'required|string',
            'image' => 'sometimes|image|max:10240',
        ]);


        if ($request->hasFile('image')){
            //Delete old image if it exists
            if($recipe->image_url){
                $oldPath = str_replace(Storage::disk('spaces')->url("images/user_uploads/user_{$recipe->user_id}/food_images"), "images/user_uploads/user_{$recipe->user_id}/food_images", $recipe->image_url);
                Storage::disk('spaces')->delete($oldPath);
            }

            $image = $request->file('image');
            $manager = new ImageManager(new Driver());
            $resizedImage = $manager->read($image->getPathname())->resize(200, 200);
            $encodedImage = $resizedImage->toJpg(75);
            $tempPath = sys_get_temp_dir() . '/' . $image->hashName() . '.jpg';
            file_put_contents($tempPath, $encodedImage);

            $path = Storage::disk('spaces')->putFileAs("images/user_uploads/user_{$recipe->user_id}/food_images", new File($tempPath), $image->hashName() . '.jpg', 'public');
            $imagesUrl = Storage::disk('spaces')->url($path);
            unlink($tempPath);

            $recipe->image_url = $imagesUrl;
        }

        // Update recipe fields
        $recipe->title = $validated['title'];
        $recipe->description = $validated['description'];
        $recipe->instruction = $validated['instruction'];
        $recipe->disease_name = $validated['disease_name'];
        $recipe->save();

      // Detach old ingredients
        $recipe->ingredients()->detach();

        // Attach new ingredients
        foreach($validated['ingredients'] as $ingredientData) {
            $ingredient = Ingredient::firstOrCreate(['name' => $ingredientData['name']]);

            // Convert fraction to decimal
            $quantity = $ingredientData['quantity'];
            if(strpos($quantity, '/') !== false) {
                $parts = explode('/', $quantity);
                if(count($parts) == 2) {
                    $quantity = floatval($parts[0]) / floatval($parts[1]);
                }
            }else {
                $quantity = floatval($quantity);
            }

            $recipe->ingredients()->attach($ingredient->id, [
                'quantity' => $quantity,
                'unit' => $ingredientData['unit']
            ]);
        }

        return redirect()->route('recipe.show', $recipe->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


}
