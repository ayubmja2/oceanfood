<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 text-center">
                    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
                        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
                            <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Edit your recipe</h2>
                        </div>
                        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
                            <form class="space-y-6" action="{{ route('recipe.update', $recipe->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')

                                {{-- Recipe Name --}}
                                <div>
                                    <label for="title" class="block text-sm font-medium leading-6 text-gray-900">Recipe Name</label>
                                    <div class="mt-2">
                                        <input id="title" name="title" type="text" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Rice and Chicken" value="{{ old('title', $recipe->title) }}">
                                    </div>
                                </div>

                                {{-- Description --}}
                                <div>
                                    <label for="description" class="block text-sm font-medium leading-6 text-gray-900">Description</label>
                                    <div class="mt-2">
                                        <input id="description" name="description" type="text" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Amazing food from... Just be descriptive about the dish" value="{{ old('description', $recipe->description) }}">
                                    </div>
                                </div>

                                {{-- Instructions --}}
                                <div>
                                    <label for="instruction" class="block text-sm font-medium leading-6 text-gray-900">Instructions</label>
                                    <div class="mt-2">
                                        <textarea id="instruction" name="instruction" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">{{ $recipe->instruction }}</textarea>
                                    </div>
                                </div>

                                {{-- Ingredients --}}
                                <div>
                                    <label for="ingredients" class="block text-sm font-medium leading-6 text-gray-900">Ingredients</label>
                                    <div id="ingredients-container" class="mt-2">
                                        @foreach($recipe->ingredients as $index => $ingredient)
                                            <div class="mt-2">
                                                <input type="text" name="ingredients[{{ $index }}][name]" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Ingredient" value="{{ old('ingredients.' . $index . '.name', $ingredient->name) }}">
                                                <input type="text" name="ingredients[{{ $index }}][quantity]" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Quantity" value="{{ old('ingredients.' . $index . '.quantity', $ingredient->pivot->quantity) }}">
                                                <input type="text" name="ingredients[{{ $index }}][unit]" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Unit (e.g., grams, cups)" value="{{ old('ingredients.' . $index . '.unit', $ingredient->pivot->unit) }}">
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" id="add-ingredient" class="mt-2 block w-full rounded-md border-0 py-1.5 text-white bg-indigo-600 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">Add Ingredient</button>
                                </div>

                                {{-- Image --}}
                                <div>
                                    <label for="image" class="block text-sm font-medium leading-6 text-gray-900">Image</label>
                                    <div class="mt-2">
                                        @if ($recipe->image_url)
                                            <img src="{{ $recipe->image_url }}" alt="Recipe Image" class="mb-2 w-32 h-32 object-cover">
                                        @endif
                                        <input id="image" name="image" type="file" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>

                                {{-- Disease Name --}}
                                <div>
                                    <label for="disease_name" class="block text-sm font-medium leading-6 text-gray-900">Disease Name</label>
                                    <div class="mt-2">
                                        <input id="disease_name" name="disease_name" type="text" autocomplete="current-password" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="EOE, etc" value="{{ old('disease_name', $recipe->disease_name) }}">
                                    </div>
                                </div>

                                <div>
                                    <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
@vite('resources/js/post-form.js')
