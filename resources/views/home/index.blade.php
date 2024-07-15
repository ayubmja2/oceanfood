<x-app-layout>
    <div class="h-screen flex">
        <!-- Left Sidebar -->
        <div class="bg-blue-800 w-1/4 h-screen sticky top-0">
            <x-sidebar :$collections/>
        </div>

        <!-- Main Content Area -->
        <div class="w-1/2 h-full overflow-y-auto p-2 pb-16">
            @foreach($recipes as $recipe)
                <x-recipe-card :$recipe />
            @endforeach
        </div>

        <!-- Right Sidebar -->
        <div class="bg-blue-800 w-1/4 h-screen sticky top-0">
            <x-sidebar-right />
        </div>
    </div>
</x-app-layout>
