<x-app-layout>
    <div class="container mx-auto">
        <x-panel class="flex flex-col mt-6">
            <div class="text-center font-medium mb-8" >
                <h1>{{$recipe->title}}</h1>
            </div>

            <div class="grid grid-cols-2 text-center gap-4 mb-8">
                <div>
                    <x-panel>
                        <h1>Instructions</h1>
                        <h2>{{$recipe->instruction}}</h2>
                    </x-panel>
                </div>
                <div>
                    <x-panel>
                        <h1>Ingredients</h1>
                       <ul>
                           @foreach($recipe->ingredients as $ingredient)
                               <li>{{$ingredient}}</li>
                           @endforeach
                       </ul>
                    </x-panel>
                </div>
            </div>

            <div class="text-center font-medium">
                <x-panel>
                    <h1>Allergiants</h1>
                </x-panel>
            </div>
        </x-panel>
    </div>
</x-app-layout>
