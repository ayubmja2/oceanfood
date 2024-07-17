<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 text-center">
                    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
                        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
                            {{--                            <img class="mx-auto h-10 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company">--}}
                            <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Post your recipe</h2>
                        </div>

                        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
                            <form class="space-y-6" action="{{route('recipe.store')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                {{-- Recipe Name --}}
                                <div>
                                    <div class="flex items-center justify-between">
                                        <label for="title" class="block text-sm font-medium leading-6 text-gray-900">Recipe Name</label>
                                    </div>
                                    <div class="mt-2">
                                        <input id="title" name="title" type="text" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Rice and Chicken">
                                    </div>
                                </div>
                                {{-- Description --}}
                                <div>
                                    <div class="flex items-center justify-between">
                                        <label for="description" class="block text-sm font-medium leading-6 text-gray-900">Description</label>
                                    </div>
                                    <div class="mt-2">
                                        <input id="description" name="description" type="text" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Amazing food from... Just be descriptive about the dish">
                                    </div>
                                </div>
                                {{-- Instructions --}}
                                <div>
                                    <div class="flex items-center justify-between">
                                        <label for="instruction" class="block text-sm font-medium leading-6 text-gray-900">Instructions</label>
                                    </div>
                                    <div class="mt-2">
                                        <input id="instruction" name="instruction" type="text" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Cook rice for 4-6min. Cook chicken in oven until cooked">
                                    </div>
                                </div>
                                {{-- Ingredients --}}
                                <div>
                                    <div class="flex items-center justify-between">
                                        <label for="ingredients" class="block text-sm font-medium leading-6 text-gray-900">Ingredients</label>
                                    </div>
                                    <div class="mt-2">
                                        <input id="ingredients" name="ingredients" type="text" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Rice, Chicken">
                                    </div>
                                </div>
                                {{-- img --}}
                                <div>
                                    <div class="flex items-center justify-between">
                                        <label for="image" class="block text-sm font-medium leading-6 text-gray-900">Image</label>
                                    </div>
                                    <div class="mt-2">
                                        <input id="image" name="image" type="file" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>
                                {{-- Disease Name --}}
                                <div>
                                    <div class="flex items-center justify-between">
                                        <label for="disease_name" class="block text-sm font-medium leading-6 text-gray-900">Disease Name</label>
                                    </div>
                                    <div class="mt-2">
                                        <input id="disease_name" name="disease_name" type="text" autocomplete="current-password" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="EOE, etc">
                                    </div>
                                </div>

                                <div>
                                    <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
