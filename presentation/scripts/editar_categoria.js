document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const idCategoria = urlParams.get('id_categoria');

    if (idCategoria) {
        loadCategoria(idCategoria);
    } else {
        console.error('ID de categoría no proporcionado en la URL');
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se ha proporcionado ID de categoría',
            confirmButtonText: 'Aceptar'
        });
    }
});

async function loadCategoria(idCategoria) {
    try {
        const response = await fetch(`http://refactorizar_proyecto.test/businessLogic/swCategoria.php?id=${idCategoria}`);
        if (!response.ok) {
            throw new Error('Error al obtener la categoría');
        }

        const categoria = await response.json();
        populateForm(categoria);
    } catch (error) {
        console.error('Error al cargar la categoría:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al cargar la categoría',
            confirmButtonText: 'Aceptar'
        });
    }
}

function populateForm(categoria) {
    const nombreInput = document.getElementById('Nombre_categoria');
    const idInput = document.getElementById('ID_categoria');

    nombreInput.value = categoria.Nombre_categoria;
    idInput.value = categoria.ID_categoria;
}

const editCategoryForm = document.getElementById('editar-categoria-form');
editCategoryForm.addEventListener('submit', (event) => {
    event.preventDefault();
    updateCategory(event);
});

async function updateCategory(event) {
    event.preventDefault();
    
    const idCategoria = document.getElementById('ID_categoria').value;
    const nombreCategoria = document.getElementById('Nombre_categoria').value;
    const imagenCategoria = document.getElementById('Imagen_categoria').files[0];

    const formData = new FormData();
    formData.append('idCategoria', idCategoria);
    formData.append('nombreCategoria', nombreCategoria);
    if (imagenCategoria) {
        formData.append('imagenCategoria', imagenCategoria);
    }

    try {
        const response = await fetch(`http://refactorizar_proyecto.test/businessLogic/swCategoria.php`, {
            method: 'PUT',
            body: formData
        });

        if (!response.ok) {
            throw new Error('Error al actualizar la categoría');
        }

        const data = await response.json();
        if (data.success) {
            // Mostrar mensaje de éxito con SweetAlert2
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: data.message,
                confirmButtonText: 'Aceptar'
            }).then(() => {
                window.location.href = '../categorias/list_categoria.php';
            });
        } else {
            // Mostrar mensaje de error con SweetAlert2
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al actualizar la categoría',
                confirmButtonText: 'Aceptar'
            });
        }
    } catch (error) {
        console.error('Error al actualizar la categoría:', error);
        // Mostrar mensaje de error genérico con SweetAlert2
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al procesar la solicitud',
            confirmButtonText: 'Aceptar'
        });
    }
}
