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
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        alert('Recipe created successfully')
                        form.reset();
                    }else {
                        alert('Failed to create recipe');
                    }
                })
                .catch(error => console.error('Error', error));
        })
    }
})
