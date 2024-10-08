<x-app-layout>
    <div class="container mx-auto">
        <x-panel class="flex flex-col mt-6">
            <div class="grid grid-cols-3 justify-items-end font-medium mb-8">
                @if($recipe->user_id === Auth::id())
                    <a class="col-start-1 justify-self-start" href="{{route('recipe.edit', $recipe->id)}}">Edit Recipe</a>
                @endif
                <h1 class="col-start-2 justify-self-center">{{$recipe->title}}</h1>
                @if($isInCategory)
                    <div class="relative">
                        <i class="fa-solid fa-ellipsis cursor-pointer" id="ellipsis-icon"></i>
                        <div id="dropdown-menu"
                             class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-20">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                               id="delete-from-category">Remove</a>
                        </div>
                    </div>
                @endif
            </div>
            <div class="container mx-auto mb-8">
                <div class="flex flex-row justify-evenly">
                    <img class="rounded-lg shadow-2xl dark:shadow-orange-500 mb-4" src="{{$recipe->image_url}}" alt="">
                </div>
            </div>

            <div class="grid grid-cols-2 text-center gap-4 mb-8">
                <div>
                    <x-panel>
                        <h1>Instructions</h1>
                        <h2>{{$recipe->instruction}}</h2>
                    </x-panel>
                </div>
                <div>
                    <x-panel>
                        <h1>Ingredients</h1>
                        <ul>
                            @foreach($recipe->ingredients as $ingredient)
                                <li>{{ $ingredient->pivot->quantity }} {{ $ingredient->pivot->unit }}
                                    of {{ $ingredient->name }}</li>
                            @endforeach
                        </ul>
                    </x-panel>
                </div>
            </div>

            <div class="text-center font-medium">
                <x-panel>
                    <h1>Allergens</h1>
                    <div class="flex flex-wrap gap-2">
                        @foreach($recipe->ingredients as $ingredient)
                            @if(in_array(strtolower($ingredient->name), array_map('strtolower', $userAllergens)))
                                <span class="bg-red-500 text-white p-2 rounded">
                                      {{ $ingredient->name }}
                                  </span>
                            @endif
                        @endforeach
                    </div>
                </x-panel>
            </div>
        </x-panel>
    </div>
</x-app-layout>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        const ellipsisIcon = document.getElementById('ellipsis-icon');
        const dropdownMenu = document.getElementById('dropdown-menu');
        const deleteFromCategory = document.getElementById('delete-from-category');

        if (ellipsisIcon) {
            ellipsisIcon.addEventListener('click', function () {
                dropdownMenu.classList.toggle('hidden');
            });

            deleteFromCategory.addEventListener('click', function (event) {
                event.preventDefault();
                dropdownMenu.classList.toggle('hidden');

                //Ajax call to delete
                $.ajax({
                    url: '{{route('category.remove-recipe')}}',
                    type: 'POST',
                    data: {
                        recipe_id: '{{$recipe->id}}',
                        _token: '{{csrf_token()}}'
                    },
                    success: function (response) {
                        if (response.success) {
                            window.location.href = '{{ route("recipebook.index") }}';
                            // location.reload();
                        } else {
                            alert('Failed to remove recipe from collection')
                        }
                    },
                    error: function (response) {
                        alert('Error occurred while removing recipe from category');
                    }
                });
            });
        }
    });
</script>
