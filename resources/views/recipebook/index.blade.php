{{--<x-app-layout>--}}
{{--    <div class="py-12">--}}
{{--        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">--}}
{{--            <div class="overflow-hidden shadow-sm sm:rounded-lg">--}}
{{--                <div class="space-y-10">--}}
{{--                    <section class="pt-10">--}}
{{--                        <div class="grid lg:grid-cols-3 gap-8 mt-6">--}}
{{--                            @foreach($bookmarkedRecipes as $recipe)--}}
{{--                                <a href="{{route('recipebook.show', $recipe)}}">--}}
{{--                                    <x-recipe-card :$recipe/>--}}
{{--                                </a>--}}
{{--                            @endforeach--}}
{{--                        </div>--}}
{{--                    </section>--}}
{{--                </div>--}}

{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</x-app-layout>--}}

<x-app-layout>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h3>Categories</h3>
                <ul id="categories">
                    @foreach($categories as $category)
                        <li class="category" data-category-id="{{$category->id}}">{{$category->title}}</li>
                    @endforeach
                </ul>

                <form action="{{route('recipebook.storeCategory')}}" method="POST">
                    @csrf
                    <input type="text" name="title" placeholder="New Category" required/>
                    <button type="submit">Add Category</button>
                </form>
            </div>

            <div class="col-md-8">
                <h3>Uncategorized Recipes</h3>
                <ul id="uncategorized-recipes">
                    @foreach($uncategorizedRecipes as $recipe)
                        <li class="recipe" data-recipe-id="{{$recipe->id}}" draggable="true">
                            <x-panel class="flex flex-col text-center">
                                <div class="self-start text-sm">{{$recipe->disease_name}}</div>
                                <div class="self-start text-sm mt-4">{{$recipe->user->name}}</div>
                                <div class="py-8">
                                    <h3 class="group-hover:text-gamboge text-xl font-bold transition-colors duration-300">{{$recipe->title}}</h3>
                                </div>
                            </x-panel>
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
