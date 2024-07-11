<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="space-y-10">
                    <section class="pt-10">
                        <div class="grid lg:grid-cols-3 gap-8 mt-6">
                            @foreach($bookmarkedRecipes as $recipe)
                                <a href="{{route('recipebook.show', $recipe)}}">
                                    <x-recipe-card :$recipe/>
                                </a>
                            @endforeach
                        </div>
                    </section>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
