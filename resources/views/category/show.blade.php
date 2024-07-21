<x-app-layout>
    <div class="container mx-auto p-4">
        <x-panel>
            <div class="grid grid-cols-3 justify-items-end font-medium mb-8">
                <h1 class="col-start-2 justify-self-center">{{$category->title}}</h1>
                <div class="relative">
                    <i class="fa-solid fa-ellipsis cursor-pointer" id="ellipsis-icon"></i>
                    <div id="dropdown-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-20">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" id="rename-category">Rename</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" id="delete-category">Delete</a>
                    </div>
                </div>
            </div>

            @if($category->recipes->isEmpty())
                <p>No recipes found in this category.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($category->recipes as $recipe)
                        <x-recipe-card :recipe="$recipe" />
                    @endforeach
                </div>
            @endif
        </x-panel>
    </div>

    <!-- Rename Category Modal -->
    <div id="rename-category-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex justify-center items-center">
        <div class="bg-white rounded-lg p-4 relative">
            <button id="close-modal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800">&times;</button>
            <h2 class="text-xl mb-4">Rename Category</h2>
            <form id="rename-category-form">
                <input type="hidden" id="rename-category-id" value="{{ $category->id }}">
                <input type="text" id="new-category-title" class="border p-2 rounded w-full mb-4" placeholder="New Category Title" required>
                <button type="submit" class="bg-blue-500 text-white p-2 rounded">Rename</button>
            </form>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {


            const ellipsisIcon = $('#ellipsis-icon');
            const dropdownMenu = $('#dropdown-menu');
            const renameCategory = $('#rename-category');
            const deleteCategory = $('#delete-category');
            const renameModal = $('#rename-category-modal');
            const closeModal = $('#close-modal');

            ellipsisIcon.on('click', function() {

                dropdownMenu.toggleClass('hidden');
            });

            renameCategory.on('click', function(event) {
                event.preventDefault();
                dropdownMenu.addClass('hidden');
                renameModal.removeClass('hidden');
            });

            closeModal.on('click', function() {
                console.log('Close modal clicked');
                renameModal.addClass('hidden');
            });

            // Close the modal when clicking outside of it
            $(window).on('click', function(event) {
                if ($(event.target).is(renameModal)) {
                    renameModal.addClass('hidden');
                }
            });

            $('#rename-category-form').on('submit', function(event) {
                event.preventDefault();

                $.ajax({
                    url: '{{ route("category.rename") }}',
                    type: 'POST',
                    data: {
                        category_id: $('#rename-category-id').val(),
                        title: $('#new-category-title').val(),
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            alert('Failed to rename category');
                        }
                    },
                    error: function(response) {
                        alert('Error occurred while renaming category');
                    }
                });
            });

            deleteCategory.on('click', function(event) {
                event.preventDefault();

                dropdownMenu.addClass('hidden');

                $.ajax({
                    url: '{{ route("category.delete") }}',
                    type: 'POST',
                    data: {
                        category_id: '{{ $category->id }}',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            window.location.href = '{{ route("recipebook.index") }}';
                        } else {
                            alert('Failed to delete category');
                        }
                    },
                    error: function(response) {
                        alert('Error occurred while deleting category');
                    }
                });
            });
        });
    </script>

</x-app-layout>

