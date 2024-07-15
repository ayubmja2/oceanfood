<!-- resources/views/categories/show.blade.php -->
<x-app-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">{{ $category->title }}</h1>

        @if($category->recipes->isEmpty())
            <p>No recipes found in this category.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($category->recipes as $recipe)
                    <x-recipe-card :recipe="$recipe" />
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
