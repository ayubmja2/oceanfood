@props(['recipe'])
<x-panel class="flex flex-col text-center">
    <div class="self-start text-sm">{{$recipe->disease_name}}</div>
    <div class="self-start text-sm mt-4">{{$recipe->user->name}}</div>
    <div class="py-8">
        <h3 class="group-hover:text-gamboge text-xl font-bold transition-colors duration-300">{{$recipe->title}}</h3>
{{--        <h4 class="group-hover:text-blue-800 text-xl font-bold transition-colors duration-300">{{$recipe->user->name}}</h4>--}}
    </div>

    <div class="flex justify-between items-center mt-auto">
        <div></div>
        <div>
{{--            @foreach($recipe->disease as $disease)--}}
{{--                <x-tag :$disease size="small"/>--}}
{{--            @endforeach--}}
            <i class="fa-solid fa-bookmark"></i>
        </div>
    </div>
</x-panel>
