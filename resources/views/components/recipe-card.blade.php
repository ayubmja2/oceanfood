@props('recipe')
<x-panel>
    <div class="self-start text-sm">{{$recipe->disease}}</div>
    <div class="py-8">
        <h3 class="group-hover:text-blue-800 text-xl font-bold transition-colors duration-300">{{$recipe->name}}</h3>
        <h4 class="group-hover:text-blue-800 text-xl font-bold transition-colors duration-300">{{$recipe->user->name}}</h4>
    </div>

    <div class="flex justify-between items-center mt-auto">
        <div>
            @foreach($recipe->disease as $disease)
                <x-tag :$disease size="small"/>
            @endforeach
        </div>
    </div>
</x-panel>
