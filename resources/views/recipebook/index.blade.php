<x-app-layout>
    <div class="container mx-auto m-4 px-4">
        <x-panel class="m-4">
            <div class="flex flex-col">
                <div class="text-center">
                    <form action="{{ route('recipebook.storeCategory') }}" method="POST">
                        @csrf
                        <input class="rounded-2xl opacity-50" type="text" name="title" placeholder="New Collection"
                               required>
                        <button
                            class="bg-orange-400 max-sm:mt-2 md:ml-4 rounded-2xl p-2 px-4 shadow dark:shadow-amber-500"
                            type="submit">
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
                            <li class="category bg-orange-400 mb-2 font-medium p-3 rounded-2xl shadow dark:shadow-yellow-500"
                                data-category-id="{{ $category->id }}">
                                <a data-category-id="{{ $category->id }}"
                                   href="{{ route('category.show', $category->id) }}">{{ $category->title }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </x-panel>

            <x-panel>
                <div class="container mx-auto">
                    <div class="text-center mb-4">
                        <h1>New BookMarks</h1>
                    </div>
                    <div class="container mx-auto">
                        <div class="h-80 overflow-y-scroll hide-scrollbar">
                            <ul id="uncategorized-recipes" class="flex flex-col space-y-2">
                                @foreach($uncategorizedRecipes as $recipe)
                                    <li class="recipe" data-recipe-id="{{ $recipe->id }}" draggable="true">
                                        <x-recipe-card :$recipe/>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                    </div>

                </div>
            </x-panel>
           <div class="container mx-auto col-span-2">
               <x-panel>
                   <div class="text-center text-md mb-3">
                       <h1>My Recipes</h1>
                   </div>
                   <div class="container mx-auto">
                       <div class="h-80 overflow-x-auto hide-scrollbar">
                           <ul class="flex">
                               @foreach($recipes as $recipe)
                                   <li class="flex-shrink-0 w-1/2">
                                       <x-recipe-card :$recipe/>
                                   </li>
                               @endforeach
                           </ul>
                       </div>
                   </div>
               </x-panel>
           </div>
        </div>

    </div>
    <style>
        .hide-scrollbar::-webkit-scrollbar{
            display:none;
        }
        .hide-scrollbar{
            -ms-overflow-style:none;
            scrollbar-width:none;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const categories = document.querySelectorAll('.category');
            const recipes = document.querySelectorAll('.recipe');
            const horizontalScrollContainers = document.querySelectorAll('.overflow-x-auto');
            recipes.forEach(recipe => {
                recipe.addEventListener('dragstart', function (e) {
                    console.log('Drag Start:', e.target.dataset.recipeId);
                    e.dataTransfer.setData('text/plain', e.target.dataset.recipeId);
                });
            });

            categories.forEach(category => {
                category.addEventListener('dragover', function (e) {
                    e.preventDefault();
                    console.log('Drag Over Category:', e.target.dataset.categoryId);
                });

                category.addEventListener('drop', function (e) {
                    e.preventDefault();
                    const recipeId = e.dataTransfer.getData('text/plain');
                    const categoryId = e.target.dataset.categoryId;


                    fetch(`/categories/${categoryId}/assign-recipe`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({recipe_id: recipeId})
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
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
            horizontalScrollContainers.forEach(container => {
                container.addEventListener('wheel', function(e) {
                    if(e.deltaY !==0 ){
                        e.preventDefault();
                        container.scrollLeft += e.deltaY;
                    }
                });
            })
        });
    </script>
</x-app-layout>
