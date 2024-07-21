@props(['recipe'])
<div class="container mx-auto p-2">
    <x-panel>
        <div class="container mx-auto max-h-full">
            <div class="text-center font-medium rounded-2xl bg-orange-400 max-md:bg-transparent md:flex flex-row justify-center max-md:transition duration-300 ease-in-out shadow-2xl dark:shadow-orange-300">
                <h3 class="text-sm rounded-md max-md:my-2 max-md:block bg-orange-400 md:transition-colors transition-colors duration-300">{{$recipe->title}}</h3>
            </div>
        </div>

        <div class="container mx-auto mt-4">
            <div class="flex max-md:flex-col-reverse max-md:justify-items-center justify-evenly md:space-x-6">
                <div class="max-md:mt-4 mx-auto">
                    <div class="text-center">
                        <div class="container">
                            <img class="rounded-lg shadow-2xl dark:shadow-orange-500 mb-4" src="{{$recipe->image_url}}" alt="">
                            <a href="{{route('recipe.show', $recipe->id)}}" class="bg-orange-400 p-2 font-medium rounded-full">Info</a>
                        </div>
                    </div>
                </div>
                <div class="container flex flex-col max-sm:text-sm overflow-hidden text-ellipsis max-sm:line-clamp-1 text-center">
                    <x-panel>
                        <p>{{$recipe->description}}</p>
                    </x-panel>
                </div>
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

    </x-panel>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('.bookmark-btn').off('click').on('click', function() {
            let recipeId = $(this).data('recipe-id');
            let button = $(this).find('.bookmark-icon');
            let buttonHtml = $(this).html();


            // Immediate visual feedback
            if (button.hasClass('far')) {
                button.removeClass('far').addClass('fas').css('color', 'black');
            } else {
                button.removeClass('fas').addClass('far').css('color', '');
            }

            $.ajax({
                url: '/recipes/' + recipeId + '/toggle-bookmark',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {

                    // Ensure the icon state is correctly updated based on the response
                    if (response.bookmarked) {
                        $(this).html('<i class="fas fa-bookmark bookmark-icon" style="color: black;"></i>');
                    } else {
                        $(this).html('<i class="far fa-bookmark bookmark-icon"></i>');
                    }

                    let recipeItem = $(this).closest('.recipe');
                    if (response.bookmarked) {
                        $('#categorized-recipes').append(recipeItem);
                    } else {
                        $('#uncategorized-recipes').append(recipeItem);
                    }
                }.bind(this), // Bind 'this' to ensure the correct context

                error: function(response) {
                    // Revert the icon state if the request fails
                    $(this).html(buttonHtml);
                    alert("Error toggling bookmark");
                }.bind(this) // Bind 'this' to ensure the correct context
            });
        });
    });
</script>
