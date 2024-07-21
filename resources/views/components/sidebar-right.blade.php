<x-panel class="mt-20 w-3/4 mx-auto max-sm:hidden">
    <div class="container">
        <div class="flex flex-col">
            <div class="grid grid-row-2 mt-4 justify-center">
                <form id="search-form">
                    @csrf
                    <input type="text" name="keyword" id="search-keyword" placeholder="Search" class="opacity-20 rounded-2xl w-3/4 mx-auto text-center"/>
                    <button class="mt-2" type="submit">Search</button>
                </form>
            </div>
        </div>
    </div>
</x-panel>

