<x-app-layout>
    <div class="container mx-auto mt-6">
        <div class="grid grid-cols-3 gap-2">
            <div class="container">
                <x-panel>
                    <div class="font-medium mb-8">
                        <h1 class="mb-4">Profile</h1>
                        <form action="{{route('profile.upload')}}" method="POST" enctype="multipart/form-data" id="profile-image-form" >
                            @csrf
                            <div class="relative w-32 h-32 mx-auto">
                                <img id="profile-image" class="rounded-full w-full h-full object-cover cursor-pointer" src="{{ $user->profile_image_url ? $user->profile_image_url : 'https://place-hold.it/150'}}" alt="user profile image">
                                <input type="file" name="profile_image" id="profile-image-input" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="document.getElementById('profile-image-form').submit();">
                            </div>
                        </form>
                        <h1>{{$user->name}}</h1>
                    </div>
                    <div>
                        @include('profile.partials.delete-user-form')
                    </div>
                </x-panel>
            </div>
            <div>
                <x-panel>
                    <div class="container">
                        <h1>Highlight Food Allergies</h1>
                        <div class="grid grid-row-2 gap-4">
                            <div class="mt-4">
                                <form action="{{route('profile.addAllergen')}}" method="POST">
                                    @csrf
                                    <input class="rounded-3xl bg-transparent" name="allergen" type="text" placeholder="Type in Allergen">
                                    <button class="bg-orange-400 p-2 px-6 ml-4 rounded-2xl"  type="submit">Submit</button>
                                </form>
                            </div>
                            <div>
                                <h1>Where Allergies are listed below:</h1>
                                <div class="flex flex-wrap text-sm mt-4 border border-orange-400 p-2 gap-2">
                                    @foreach($user->allergens ?? [] as $allergen)
                                       <div class="relative p-2 bg-orange-400 rounded-lg flex items-center justify-between w-full md:w-auto">
                                           <h1>{{$allergen}}</h1>
                                           <form action="{{route('profile.removeAllergen')}}" method="POST" class="absolute top-0 right-0">
                                               @csrf
                                               <input type="hidden" name="allergen" value="{{$allergen}}">
                                               <button type="submit" class="text-red-500">&times;</button>
                                           </form>
                                       </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </x-panel>
            </div>
            <div>
                <x-panel>
                    <div class="container">
                        @include('profile.partials.update-profile-information-form')
                        @include('profile.partials.update-password-form')
                    </div>
                </x-panel>
            </div>

        </div>
    </div>
</x-app-layout>
<script type="module">
    document.getElementById('profile-image').addEventListener('click', function() {
        document.getElementById('profile-image-input').click()
    })
</script>
