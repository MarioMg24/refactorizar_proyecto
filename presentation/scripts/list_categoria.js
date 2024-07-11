document.addEventListener('DOMContentLoaded', () => {
    loadCategories();
});

async function loadCategories() {
    try {
        const response = await fetch('http://refactorizar_proyect.test/businessLogic/swCategoria.php');
        if (!response.ok) {
            throw new Error('Error al obtener las categorías');
        }

        const categorias = await response.json();
        displayCategories(categorias);
    } catch (error) {
        console.error('Error al cargar las categorías:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al cargar las categorías',
            confirmButtonText: 'Aceptar'
        });
    }
}

function displayCategories(categorias) {
    const categoriasContainer = document.getElementById('categorias-container');
    categoriasContainer.innerHTML = '';

    categorias.forEach(categoria => {
        const categoryCard = document.createElement('div');
        categoryCard.classList.add('bg-white', 'shadow-md', 'rounded-lg', 'p-4');

        const img = document.createElement('img');
        img.src = categoria.Imagen_categoria;
        img.alt = categoria.Nombre_categoria;
        img.classList.add('w-full', 'h-32', 'object-cover', 'mb-4');

        const title = document.createElement('h3');
        title.classList.add('text-xl', 'font-bold', 'mb-2', 'text-gray-800');
        title.textContent = categoria.Nombre_categoria;

        categoryCard.appendChild(img);
        categoryCard.appendChild(title);

        categoriasContainer.appendChild(categoryCard);
    });
}
