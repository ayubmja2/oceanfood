<?php

    use App\Http\Controllers\CategoryController;
    use App\Http\Controllers\RecipeController;
    use App\Http\Controllers\ProfileController;
    use App\Http\Controllers\RecipeBookController;
    use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//Route::get('/home', function () {
//    return view('home.index');
//})->middleware(['auth', 'verified'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/upload', [ProfileController::class, 'uploadProfileImage'])->name('profile.upload');
    Route::post('/profile/add-allergen', [ProfileController::class, 'addAllergen'])->name('profile.addAllergen');
    Route::post('/profile/remove-allergen', [ProfileController::class, 'removeAllergen'])->name('profile.removeAllergen');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

//    Home Route
    Route::get('/home', [RecipeController::class, 'index'])->name('home');

//    Post route
    Route::get('/post', function(){
        return view('post.index');
    })->name('post');

//    recipe route
    Route::get('/recipe/{id}', [RecipeController::class, 'show'])->name('recipe.show');

    Route::get('recipe/{id}/edit', [RecipeController::class, 'edit'])->name('recipe.edit');
    Route::patch('recipe/{id}', [RecipeController::class, 'update'])->name('recipe.update');


    Route::get('/recipe', [RecipeController::class, 'index'])->name('recipe');
    Route::post('/recipe', [RecipeController::class, 'store'])->name('recipe.store');
    Route::get('/recipe/latest', [RecipeController::class, 'latest'])->name('recipe.latest');
    Route::get('/recipes/search', [RecipeController::class, 'search'])->name('recipe.search');

    Route::get('/recipes/new', [RecipeController::class, 'fetchNewRecipes'])->name('recipe.new');
    //Category
    Route::get('/category/{id}', [CategoryController::class, 'show'])->name('category.show');
    Route::post('/category/remove-recipe', [RecipeController::class, 'removeFromCategory'])->name('category.remove-recipe');
    Route::post('/category/delete', [CategoryController::class, 'delete'])->name('category.delete');
    Route::post('/category/rename', [CategoryController::class, 'rename'])->name('category.rename');


//    recipe book route
    Route::get('/recipebook', [RecipeBookController::class, 'index'])->name('recipebook.index'); //
    Route::get('/recipebook/{id}', [RecipeBookController::class, 'show'])->name('recipebook.show');
    Route::post('/recipebook/category', [RecipeBookController::class, 'storeCategory'])->name('recipebook.storeCategory');
    Route::post('/categories/{category}/assign-recipe', [RecipeBookController::class, 'assignRecipe']);



//    bookmarking route
    Route::post('/recipes/{id}/toggle-bookmark',[RecipeBookController::class, 'toggleBookmark'])->name('recipes.toggleBookmark');

//// load more route
//    Route::get('/recipes/loadMore', [RecipeController::class, 'loadMore'])->name('recipes.loadMore');
//    Route::get('/recipes/check-new', [RecipeController::class, 'checkNew'])->name('recipes.checkNew');
});

require __DIR__.'/auth.php';
