@props(['collections'])
<x-panel class="mt-20 w-3/4 mx-auto max-sm:hidden">
    <div class="container">
        <div class="flex flex-col">
            <div class="text-center block">
                <h1>Collection</h1>
            </div>
            <div class="grid grid-row-2 mt-4 justify-center">
               @foreach($collections as $category)
                    <div class="bg-orange-400 mb-8 rounded-2xl  text-sm p-4 text-center">{{$category->title}}</div>
               @endforeach
            </div>
        </div>
    </div>
</x-panel>

