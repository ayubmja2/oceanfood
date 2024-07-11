<x-app-layout>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="flex justify-end">
                    <form action="{{ route('recipebook.storeCategory') }}" method="POST">
                        @csrf
                        <input type="text" name="title" placeholder="New Category" required>
                        <button type="submit">Add Category</button>
                    </form>
                </div>
                <h3 class="mt-6 mb-6 text-center">Categories</h3>
                <ul id="categories">
                    @foreach($categories as $category)
                        <li class="category" data-category-id="{{ $category->id }}" style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
                            {{ $category->title }}
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="col-md-8 text-center">
                <h3>Uncategorized Recipes</h3>
                <ul class="mt-6" id="uncategorized-recipes" style="list-style: none; padding: 0;">
                    @foreach($uncategorizedRecipes as $recipe)
                        <li class="recipe" data-recipe-id="{{ $recipe->id }}" draggable="true" style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
{{--                            <x-panel class="flex flex-col text-center">--}}
{{--                                <div class="self-start text-sm">{{$recipe->disease_name}}</div>--}}
{{--                                <div class="self-start text-sm mt-4">{{$recipe->user->name}}</div>--}}
{{--                                <div class="py-8">--}}
{{--                                    <h3 class="group-hover:text-gamboge text-xl font-bold transition-colors duration-300">{{ $recipe->title }}</h3>--}}
{{--                                </div>--}}
{{--                            </x-panel>--}}
                            <x-recipe-card :$recipe />
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categories = document.querySelectorAll('.category');
            const recipes = document.querySelectorAll('.recipe');

            recipes.forEach(recipe => {
                recipe.addEventListener('dragstart', function(e) {
                    console.log('Drag Start:', e.target.dataset.recipeId);
                    e.dataTransfer.setData('text/plain', e.target.dataset.recipeId);
                });
            });

            categories.forEach(category => {
                category.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    console.log('Drag Over Category:', e.target.dataset.categoryId);
                });

                category.addEventListener('drop', function(e) {
                    e.preventDefault();
                    const recipeId = e.dataTransfer.getData('text/plain');
                    const categoryId = e.target.dataset.categoryId;

                    console.log('Drop:', { recipeId, categoryId });

                    fetch(`/categories/${categoryId}/assign-recipe`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ recipe_id: recipeId })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                console.log('Recipe assigned successfully');
                                location.reload();
                            } else {
                                console.error('Failed to assign recipe');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });
            });
        });
    </script>
</x-app-layout>
