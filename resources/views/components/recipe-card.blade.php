@props(['recipe'])
<x-panel class="flex flex-col text-center mb-4">
    <div class="self-start text-sm">{{$recipe->disease_name}}</div>
    <div class="self-start text-sm mt-4">{{$recipe->user->name}}</div>
    <div class="py-8">
        <h3 class="group-hover:text-gamboge text-xl font-bold transition-colors duration-300">{{$recipe->title}}</h3>
{{--        <h4 class="group-hover:text-blue-800 text-xl font-bold transition-colors duration-300">{{$recipe->user->name}}</h4>--}}
    </div>

    <div class="flex justify-between items-center mt-auto">
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
</x-panel>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

<script>
    $(document).ready(function() {
        $('.bookmark-btn').on('click', function() {
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
                success: function(response) {
                    if(response.bookmarked) {
                        button.find('.bookmark-icon').removeClass('far').addClass('fas'); // Change to solid icon
                        button.find('.bookmark-icon').css('color', 'blue'); // Change color to indicate bookmarked
                    } else {
                        button.find('.bookmark-icon').removeClass('fas').addClass('far'); // Change to regular icon
                        button.find('.bookmark-icon').css('color', ''); // Reset color to default
                    }
                },
                error: function(response) {
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
