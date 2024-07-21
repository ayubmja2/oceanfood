import Echo from 'laravel-echo';
import Pusher from "pusher-js";


window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});

window.Echo.channel('recipes')
    .listen('RecipeCreated', (e) => {
    console.log('New recipe created:', e.recipe);
    fetchNewRecipe();
});

function fetchNewRecipes() {
    fetch ('/recipes/latest')
        .then(response => response.json())
        .then(recipes => {
            updateRecipeFeed(recipes);
        }).catch(error => console.error('Error fetching new recipes:', error));
}

function updateRecipeFeed(recipes) {
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = '';
    recipes.forEach(recipe => {
        mainContent.insertAdjacentHTML('beforeend', recipe.component_html);
    });
}
