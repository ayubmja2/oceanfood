<x-app-layout>
    <meta id="new-recipes-url" content="{{ route('recipe.new') }}">
    <div class="h-screen flex mt-2">
        <!-- Left Sidebar -->
        <div class="w-1/4 h-screen sticky top-0">
            <x-sidebar :$collections/>
        </div>

        <!-- Main Content Area -->
        <div class="w-1/2 h-full overflow-y-auto no-scrollbar p-4 pb-16" id="main-content">
            <div id="notification-button" class="hidden p-2 bg-blue-500 text-white rounded-full" data-count="0">
                You have (0) new posts
            </div>
            <div id="recipe-cards">
                @foreach($recipes as $recipe)
                    <x-recipe-card :recipe="$recipe" />
                @endforeach
            </div>
            <div id="spinner" class="hidden text-center my-4">
                <i class="fa fa-spinner fa-spin fa-2x"></i>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="w-1/4 h-screen sticky top-0">
            <x-sidebar-right class="block" />
{{--            <x-sidebar-right-profile class="block" />--}}
        </div>
    </div>
</x-app-layout>
@vite('resources/js/echo-subscription.js')
<script type="module">
    $(document).ready(function() {
        let currentPage = 1;
        let lastSearchKeyword = '';

        function loadRecipes(keyword, page = 1, append = false) {
            $.ajax({
                url: '{{ route('recipe.search') }}',
                type: 'GET',
                data: { keyword: keyword, page: page },
                beforeSend: function() {
                    $('#spinner').removeClass('hidden');
                },
                success: function(response) {
                    $('#spinner').addClass('hidden');
                    if (response.html) {
                        if (append) {
                            $('#recipe-cards').append(response.html);
                        } else {
                            $('#recipe-cards').html(response.html);
                        }
                    } else {
                        alert(response.error || 'No recipes found.');
                    }
                },
                error: function(response) {
                    $('#spinner').addClass('hidden');
                    if (response.responseJSON.error) {
                        alert(response.responseJSON.error);
                    } else {
                        alert('An error occurred.');
                    }
                }
            });
        }

        $('#search-form').on('submit', function(event) {
            event.preventDefault();
            let keyword = $('#search-keyword').val();
            lastSearchKeyword = keyword;
            currentPage = 1; // Reset the page counter
            loadRecipes(keyword);
        });

        $('#search-keyword').on('input', function() {
            if ($(this).val() === '') {
                lastSearchKeyword = '';
                currentPage = 1; // Reset the page counter
                loadRecipes('');
            }
        });

        $(document).on('click', '#load-more', function() {
            currentPage++;
            loadRecipes(lastSearchKeyword, currentPage, true);
        });

        // Bookmark AJAX
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

