<x-app-layout>
    <div class="container">
        <h1>{{$recipebook->title}}</h1>
        <h1>{{$recipebook->user->name}}</h1>
        <h1>{{$recipebook->ingredients}}</h1>
        <h1>{{$recipebook->instruction}}</h1>
    </div>
</x-app-layout>
