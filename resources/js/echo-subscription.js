document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM fully loaded and parsed');

    window.Echo.private('recipes')
        .listen('RecipeCreated', (e) => {
            console.log('New recipe created:', e.recipe);

            // Show the notification button
            const notificationButton = document.getElementById('notification-button');
            let count = parseInt(notificationButton.getAttribute('data-count')) || 0;
            count++;
            notificationButton.setAttribute('data-count', count);
            notificationButton.textContent = `You have (${count}) new post${count > 1 ? 's' : ''}`;
            notificationButton.classList.remove('hidden');
        });
});
