@props(['recipe'])
<x-panel class="flex flex-col mb-4">
    <div>
        <div class="text-center font-medium rounded-2xl bg-orange-400 max-md:bg-transparent md:flex flex-row justify-between p-2 max-md:transition duration-300 ease-in-out shadow-2xl">
            <div class="rounded-md max-md:block bg-orange-400 md:bg-transparent">{{$recipe->disease_name}}</div>
            <h3 class="rounded-md max-md:my-2 max-md:block bg-orange-400 md:transition-colors group-hover:text-gamboge transition-colors duration-300" >{{$recipe->title}}</h3>
            <div class="rounded-md max-md:block bg-orange-400 md:bg-transparent">{{$recipe->user->name}}</div>
        </div>

        <div class="mt-8 ">
            <div class="flex max-md:flex-col-reverse max-md:justify-items-center justify-evenly space-x-6">
                <div class="max-md:mt-4">
                    <img class="rounded-lg shadow-2xl mr-10 dark:shadow-gray-800" src="https://place-hold.it/200/200" alt="">
                </div>

                <div class="flex flex-col  flex-wrap max-sm:hidden max-auto text-center p-6 bg-white/20 rounded-xl">
                    <h1 class="font-medium">Recipe Description</h1>
                    <div class="flex">
                        <p class="sm:text-sm overflow-hidden text-ellipsis break-words max-h-32 line-clamp-2 text-balance">
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                        </p>
                    </div>
                </div>

            </div>
        </div>

        <div class="flex justify-between items-center mt-2 max-md:hidden max-md:transition duration-600 ease-in-out">
            <div></div>
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
