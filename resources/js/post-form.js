document.addEventListener('DOMContentLoaded', function(){
    const form = document.getElementById('recipe-form');
    if(form){
        form.addEventListener('submit', function(e){
            e.preventDefault();

            const formData = new FormData(form);
            const url = form.action;

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
                .then(response => {
                    return response.text().then(text => {
                        try{
                            return JSON.parse(text);
                        }catch(error){
                            console.log('Failed to parse JSON:', text);
                            throw error;
                        }
                    });
                })
                .then(data=> {
                    if(data.success) {
                        alert('Recipe created successfully');
                        form.reset();
                    }else {
                        alert('Failed to create recipe');
                    }
                }).catch(error => console.error('Error:', error));
        });
    }
});

document.getElementById('add-ingredient').addEventListener('click', function() {
    let container = document.getElementById('ingredients-container');
    let index = container.children.length / 3; // Each ingredient has 3 input fields
    let ingredientHtml = `
        <div class="mt-2">
            <input type="text" name="ingredients[${index}][name]" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Ingredient">
            <input type="text" name="ingredients[${index}][quantity]" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Quantity">
            <input type="text" name="ingredients[${index}][unit]" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Unit (e.g., grams, cups)">
        </div>`
    container.insertAdjacentHTML('beforeend', ingredientHtml)
});
