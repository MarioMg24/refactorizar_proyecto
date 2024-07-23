document.addEventListener('DOMContentLoaded', () => {
    loadCategories();
});

async function loadCategories() {
    try {
        const response = await fetch('http://refactorizar_proyecto.test/businessLogic/swCategoria.php');
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
        categoryCard.classList.add('bg-white', 'shadow-md', 'rounded-lg', 'p-4', 'mb-4', 'cursor-pointer');

        const img = document.createElement('img');
        img.src = categoria.Imagen_categoria;
        img.alt = categoria.Nombre_categoria;
        img.classList.add('w-full', 'h-32', 'object-cover', 'mb-4');

        const title = document.createElement('h3');
        title.classList.add('text-xl', 'font-bold', 'mb-2', 'text-gray-800');
        title.textContent = categoria.Nombre_categoria;

        categoryCard.appendChild(img);
        categoryCard.appendChild(title);

        if (isAdmin) {
            const editButton = createButton('Editar', 'bg-blue-500', () => editCategoria(categoria.ID_categoria));
            const deleteButton = createButton('Eliminar', 'bg-red-500', () => confirmDeleteCategoria(categoria.ID_categoria));

            categoryCard.appendChild(editButton);
            categoryCard.appendChild(deleteButton);
        }

        categoryCard.addEventListener('click', () => {
            window.location.href = `../productos/list_producto.php?idcategoria=${categoria.ID_categoria}`;
        });

        categoriasContainer.appendChild(categoryCard);
    });
}

function createButton(text, bgColor, onClick) {
    const button = document.createElement('button');
    button.textContent = text;
    button.classList.add(bgColor, 'text-white', 'px-4', 'py-2', 'rounded-md', 'shadow-sm', 'hover:bg-opacity-80', 'focus:outline-none', 'focus:ring-2', 'focus:ring-offset-2', 'focus:ring-opacity-50', 'mr-2');
    button.addEventListener('click', (event) => {
        event.stopPropagation();
        onClick();
    });
    return button;
}

function editCategoria(idCategoria) {
    window.location.href = `editar_categoria.php?id_categoria=${idCategoria}`;
}

function confirmDeleteCategoria(idCategoria) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "También se eliminarán los productos asociados a esta categoría.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminarlo'
    }).then((result) => {
        if (result.isConfirmed) {
            deleteCategoria(idCategoria);
        }
    });
}

async function deleteCategoria(idCategoria) {
    try {
        const response = await fetch(`http://refactorizar_proyecto.test/businessLogic/swCategoria.php?id=${idCategoria}`, {
            method: 'DELETE'
        });
        const result = await response.json();
        
        if (result.success) {
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: result.message,
                confirmButtonText: 'Aceptar'
            }).then(() => {
                loadCategories(); // Recargar las categorías
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: result.message,
                confirmButtonText: 'Aceptar'
            });
        }
    } catch (error) {
        console.error('Error al eliminar la categoría:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al eliminar la categoría',
            confirmButtonText: 'Aceptar'
        });
    }
}