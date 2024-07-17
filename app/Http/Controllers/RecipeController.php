<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Recipe;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $recipes = Recipe::all();
        $user = Auth::user();
        $collections = $user->categories;
        return view('home.index', compact('recipes', 'collections'));
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
            'ingredients' => 'required|string',
            'disease_name' => 'required|string',
            'image' => 'required|image|max:10240',
        ]);

        //Handle the file upload
        if ($request->hasFile('image')) {
            $user = $request->user();
            $image = $request->file('image');
        }

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

        $path = Storage::disk('spaces')->putFileAs("images/user_uploads/user_{$user->id}", new File($tempPath), $image->hashName()
            . '.jpg', 'public');

        $imageUrl = Storage::disk('spaces')->url($path);

        // Delete the temporary file
        unlink($tempPath);

        // Ensure ingredients are stored in the ingredient table
        $ingredients = array_map('trim', explode(',', $request->input('ingredients')));

        foreach ($ingredients as $ingredient) {
            Ingredient::firstOrCreate(['name' => $ingredient]);
        }

        $request->user()->recipes()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'instruction' => $validated['instruction'],
            'ingredients' => $ingredients, // save ingredients as json array
            'disease_name' => $validated['disease_name'],
            'image_url' => $imageUrl ?? null,
        ]);
        return redirect()->route('home');
    }

    /**
     * Display the specified resource.
     */
    public function show($recipe)
    {
        $recipe = Recipe::with('user')->findOrFail($recipe);
        return view('recipe.show', compact('recipe'));
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
