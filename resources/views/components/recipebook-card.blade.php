@props(['recipe'])
<x-panel class="flex flex-col mb-4">
    <div>
        <div class="text-center font-medium rounded-2xl bg-orange-400 max-md:bg-transparent md:flex flex-row justify-between p-2 max-md:transition duration-300 ease-in-out shadow-2xl dark:shadow-orange-300">
            <div class="rounded-md max-md:block bg-orange-400 md:bg-transparent">{{$recipe->disease_name}}</div>
            <h3 class="rounded-md max-md:my-2 max-md:block bg-orange-400 md:transition-colors group-hover:text-gamboge transition-colors duration-300" >{{$recipe->title}}</h3>
            <div class="rounded-md max-md:block bg-orange-400 md:bg-transparent">{{$recipe->user->name}}</div>
        </div>

        <div class="mt-8 ">
            <div class="flex max-md:flex-col-reverse max-md:justify-items-center justify-evenly  md:space-x-6">
                <div class="max-md:mt-4 max-md:mx-auto">
                    <div class="text-center">
                        <img class="rounded-lg shadow-2xl dark:shadow-orange-500 mb-4" src="{{$recipe->image_url}}" alt="">
                        <a href="{{route('recipe.show',$recipe->id)}}" class="bg-orange-400 p-2 font-medium rounded-full">Info</a>
                    </div>
                </div>

                <x-panel class="flex flex-col flex-wrap max-sm:hidden max-auto text-center">
                    <div class="">
                        <h1 class="font-medium">{{$recipe->title}}</h1>
                    </div>

                    <div class="sm:text-sm overflow-hidden text-ellipsis break-words line-clamp-2 text-balance">
                        <p class="w-full">
                            {{$recipe->description}}
                        </p>
                    </div>
                </x-panel>

            </div>
        </div>

        <div class="flex justify-between items-center mt-2 max-sm:hidden max-md:transition duration-600 ease-in-out">
            <div>

            </div>
            <div>
                @auth
                    <button class="bookmark-btn" data-recipe-id="{{ $recipe->id }}">
                        <i class="fa{{ Auth::user()->bookmarkedRecipes->contains($recipe->id) ? 's' : 'r' }} fa-bookmark bookmark-icon"></i>
                    </button>
                @else
                    <i class="far fa-bookmark bookmark-icon"></i>
                @endauth
            </div>
        </div>
    </div>
</x-panel>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

<script>
    $(document).ready(function () {
        $('.bookmark-btn').on('click', function () {
            var recipeId = $(this).data('recipe-id');
            var button = $(this).find('.bookmark-icon');
            // Toggle the icon immediately for visual feedback
            if (button.hasClass('far')) {
                button.removeClass('far').addClass('fas').css('color', 'blue'); // Assume it's bookmarked
            } else {
                button.removeClass('fas').addClass('far').css('color', ''); // Assume it's unbookmarked
            }

            $.ajax({
                url: '/recipes/' + recipeId + '/toggle-bookmark',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.bookmarked) {
                        button.find('.bookmark-icon').removeClass('far').addClass('fas'); // Change to solid icon
                        button.find('.bookmark-icon').css('color', 'blue'); // Change color to indicate bookmarked
                    } else {
                        button.find('.bookmark-icon').removeClass('fas').addClass('far'); // Change to regular icon
                        button.find('.bookmark-icon').css('color', ''); // Reset color to default
                    }
                },
                error: function (response) {
                    // Revert the icon state if the request fails
                    if (button.hasClass('fas')) {
                        button.removeClass('fas').addClass('far').css('color', '');
                    } else {
                        button.removeClass('far').addClass('fas').css('color', 'blue');
                    }
                    alert('Error toggling bookmark');
                }
            });
        });
    });
</script>
