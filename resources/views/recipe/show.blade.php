<x-app-layout>
    <div class="container mx-auto">
        <x-panel class="flex flex-col mt-6">
            <div class="grid grid-cols-3 justify-items-end font-medium mb-8">
                <h1 class="col-start-2 justify-self-center">{{$recipe->title}}</h1>
               @if($isInCategory)
                   <div class="relative">
                       <i class="fa-solid fa-ellipsis cursor-pointer" id="ellipsis-icon"></i>
                       <div id="dropdown-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-20">
                           <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" id="delete-from-category">Remove</a>
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
                               <li>{{$ingredient}}</li>
                           @endforeach
                       </ul>
                    </x-panel>
                </div>
            </div>

            <div class="text-center font-medium">
                <x-panel>
                    <h1>Allergiants</h1>
                </x-panel>
            </div>
        </x-panel>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function(){
            const ellipsisIcon = document.getElementById('ellipsis-icon');
            const dropdownMenu = document.getElementById('dropdown-menu');
            const deleteFromCategory = document.getElementById('delete-from-category');

            if (ellipsisIcon) {
                ellipsisIcon.addEventListener('click', function() {
                    dropdownMenu.classList.toggle('hidden');
                });

                deleteFromCategory.addEventListener('click', function(event) {
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
                        success: function(response) {
                            if(response.success){
                                window.location.href = '{{ route("recipebook.index") }}';
                                // location.reload();
                            }else {
                                alert('Failed to remove recipe from collection')
                            }
                        },
                        error: function(response) {
                            alert('Error occurred while removing recipe from category');
                        }
                    });
                });
            }
        });
    </script>
</x-app-layout>
