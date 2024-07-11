<x-app-layout>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h3>Categories</h3>
                <ul id="categories">
                    @foreach($categories as $category)
                        <li>{{$category->title}}</li>
                    @endforeach
                </ul>
                <form action="{{route('category.store')}} method='POST">
                    @csrf
                    <input type="text" name="title" placeholder="New Category" required>
                    <button type="submit">Add Category</button>
                </form>
            </div>

            <div class="col-md-8">
                <h3>Uncategorized Recipes</h3>
                <ul id="uncategorized-recipes">
                    @foreach($uncategorizedRecipes as $recipe)
                        <li class="recipe" data-recipe-id="{{$recipe->id}}">{{$recipe->title}}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categories = document.querySelectorAll('#categories li');
            const recipes = document.querySelectorAll('.recipe');

            recipes.forEach(recipe => {
                recipe.draggable = true;

                recipe.addEventListener('dragstart', function(e) {
                    e.dataTransfer.setData('text/plain', e.target.dataset.recipeId);
                });
            });
            categories.forEach(category => {
               category.addEventListener('dragover', function(e) {
                   e.preventDefault();
               });
               category.addEventListener('drop', function (e){
                   e.preventDefault();
                   const recipeId = e.dataTransfer.getData('text/plain');
                   const categoryId = category.dataset.categoryId;

                   fetch(`/categories/${categoryId}/assign-recipe`, {
                       method: 'POST',
                       headers: {
                           'Content-Type': 'application/json',
                           'x-CSRF-TOKEN': '{{csrf_token()}}'
                       },
                       body: JSON.stringify({ recipe_id: recipeId})
                   }).then(response => response.json()).then(data => {
                       if (data.status === 'success') {
                           location.reload();
                       }
                   });
               });
            });
        });
    </script>
</x-app-layout>
