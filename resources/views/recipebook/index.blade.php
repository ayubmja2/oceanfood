<x-app-layout>
    <div class="container mx-auto m-4 px-4 overflow-y-auto">
        <x-panel class="m-4">
            <div class="flex flex-col">
                <div class="text-center">
                    <form action="{{ route('recipebook.storeCategory') }}" method="POST">
                        @csrf
                        <input class="rounded-2xl opacity-50" type="text" name="title" placeholder="New Collection" required>
                        <button class="bg-orange-400 max-sm:mt-2 md:ml-4 rounded-2xl p-2 px-4 shadow dark:shadow-amber-500" type="submit">
                            New Collection
                        </button>
                    </form>
                </div>
            </div>
        </x-panel>

        <div class="grid grid-cols-2 m-4 gap-2">
            <x-panel>
                <div class="flex flex-col space-y-2 text-center">
                    <div>
                        <h1>New Collections</h1>
                    </div>
                    <ul id="categories" class="flex flex-wrap justify-evenly max-sm:flex-col text-center">
                        @foreach($categories as $category)
                            <li class="category bg-yellow-500 mb-2 font-medium p-3 rounded-2xl shadow dark:shadow-yellow-500" data-category-id="{{ $category->id }}">
                                <a  data-category-id="{{ $category->id }}" href="{{ route('category.show', $category->id) }}">{{ $category->title }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </x-panel>

            <x-panel>
                <div class="flex flex-col space-y-2 text-center">
                    <div>
                        <h1>New BookMarks</h1>
                    </div>
                    <ul id="uncategorized-recipes" class="flex flex-wrap justify-evenly">
                        @foreach($uncategorizedRecipes as $recipe)
                            <li class="recipe m-2" style="flex: 0 1 calc(23% - 1em);" data-recipe-id="{{ $recipe->id }}" draggable="true">
                                <x-recipe-card :$recipe />
                            </li>
                        @endforeach
                    </ul>
                </div>
            </x-panel>
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
