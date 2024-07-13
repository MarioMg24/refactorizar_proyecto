const categoryForm = document.getElementById('form-agregar-categoria');
categoryForm.addEventListener('submit', (event) => {
    event.preventDefault();
    addCategory(event);
});

async function addCategory(event) {
    const nombreCategoria = document.getElementById('nombre_categoria').value;
    const imagenCategoria = document.getElementById('imagen_categoria').files[0]; // Obtener la imagen seleccionada
    const formData = new FormData();
    formData.append('nombreCategoria', nombreCategoria);
    formData.append('imagenCategoria', imagenCategoria);

    try {
        const response = await fetch('http://refactorizar_proyecto.test/businessLogic/swCategoria.php', {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            throw new Error('Error al añadir la categoría');
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
                text: 'Error al agregar la categoría',
                confirmButtonText: 'Aceptar'
            });
        }
    } catch (error) {
        console.error('Error al añadir la categoría:', error);
        // Mostrar mensaje de error genérico con SweetAlert2
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al procesar la solicitud',
            confirmButtonText: 'Aceptar'
        });
    }
}
