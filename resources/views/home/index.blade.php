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

        $('#search-form').on('submit', function(event) {
            event.preventDefault();
            let keyword = $('#search-keyword').val();
            console.log('Keyword:', keyword); // Debugging statement

            $.ajax({
                url: '{{ route('recipe.search') }}',
                type: 'GET',
                data: { keyword: keyword },
                beforeSend: function() {
                    $('#spinner').removeClass('hidden');
                },
                success: function(response) {
                    $('#spinner').addClass('hidden');
                    if (response.html) {
                        $('#recipe-cards').html(response.html);
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
        });

        $('#search-keyword').on('input', function() {
            if ($(this).val() === '') {
                $.ajax({
                    url: '{{ route('recipe.search') }}',
                    type: 'GET',
                    data: { keyword: '' },
                    beforeSend: function() {
                        $('#spinner').removeClass('hidden');
                    },
                    success: function(response) {
                        $('#spinner').addClass('hidden');
                        if (response.html) {
                            $('#recipe-cards').html(response.html);
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
        });
    });
</script>
