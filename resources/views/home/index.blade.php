<x-app-layout>
    <div class="h-screen flex mt-2">
        <!-- Left Sidebar -->
        <div class="w-1/4 h-screen sticky top-0">
            <x-sidebar :$collections/>
        </div>

        <!-- Main Content Area -->
        <div class="w-1/2 h-full overflow-y-auto no-scrollbar p-4 pb-16" id="main-content">
            @foreach($recipes as $recipe)
                <x-recipe-card :recipe="$recipe" />
            @endforeach
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
