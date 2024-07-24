document.addEventListener('DOMContentLoaded', function () {

   if(window.Echo) {
       window.Echo.private('recipe')
           .listen('RecipeCreated', (e) => {
               console.log('New recipe created:', e.recipe);

               // Show the notification button
               const notificationButton = document.getElementById('notification-button');
               if (notificationButton) {
                   let count = parseInt(notificationButton.getAttribute('data-count')) || 0;
                   count++;
                   notificationButton.setAttribute('data-count', count);
                   notificationButton.textContent = `You have (${count}) new post${count > 1 ? 's' : ''}`;
                   notificationButton.classList.remove('hidden');
                   console.log('Notification button updated');
               } else {
                   console.log('Notification button not found');
               }
           });

       document.getElementById('notification-button').addEventListener('click', function () {
           location.reload()
       });
   }
});

function fetchNewRecipes() {
    const notificationButton = document.getElementById('notification-button');
    const newRecipesUrl = document.querySelector('meta[name="new-recipes-url"]').getAttribute('content');

    // Get the last recipe ID currently displayed
    const lastRecipeId = document.querySelector('#recipe-cards .recipe:last-child')?.dataset.recipeId || 0;

    $.ajax({
        url: newRecipesUrl,
        type: 'GET',
        data: { last_recipe_id: lastRecipeId },
        beforeSend: function() {
            $('#spinner').removeClass('hidden');
        },
        success: function(response) {
            $('#spinner').addClass('hidden');
            if (response.html) {
                // Prepend new recipes to the top of the feed
                $('#recipe-cards').prepend(response.html);

                // Reset the notification button
                notificationButton.setAttribute('data-count', '0');
                notificationButton.textContent = 'You have (0) new posts';
                notificationButton.classList.add('hidden');
            } else {
                alert(response.error || 'No new recipes found.');
            }
        },
        error: function(response) {
            $('#spinner').addClass('hidden');
            alert('An error occurred while fetching new recipes.');
        }
    });
}
