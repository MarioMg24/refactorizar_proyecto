const productoForm = document.getElementById('registro-producto-form');
productoForm.addEventListener('submit', (event) => {
    event.preventDefault();
    addProducto(event);
});

document.addEventListener('DOMContentLoaded', () => {
    loadCategorias();
});

async function loadCategorias() {
    try {
        const response = await fetch('http://refactorizar_proyecto.test/businessLogic/swCategoria.php');
        if (!response.ok) {
            throw new Error('Error al cargar las categorías');
        }
        const categorias = await response.json();
        const selectCategoria = document.getElementById('categoria-producto');
        categorias.forEach(categoria => {
            const option = document.createElement('option');
            option.value = categoria.ID_categoria;
            option.textContent = categoria.Nombre_categoria;
            selectCategoria.appendChild(option);
        });
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

async function addProducto(event) {
    const nombreProducto = document.getElementById('nombre-producto').value;
    const descripcionProducto = document.getElementById('descripcion-producto').value;
    const precioProducto = document.getElementById('precio-producto').value;
    const imagenProducto = document.getElementById('imagen-producto').files[0];
    const cantidadProducto = document.getElementById('cantidad-producto').value;
    const categoriaId = document.getElementById('categoria-producto').value;
    const fechaCaducidad = document.getElementById('fecha-caducidad').value;

    const formData = new FormData();
    formData.append('nombreProducto', nombreProducto);
    formData.append('descripcionProducto', descripcionProducto);
    formData.append('precioProducto', precioProducto);
    formData.append('imagenProducto', imagenProducto);
    formData.append('cantidadDisponible', cantidadProducto);
    formData.append('categoriaId', categoriaId);
    formData.append('fechaVencimiento', fechaCaducidad);

    try {
        const response = await fetch('http://refactorizar_proyecto.test/businessLogic/swProducto.php', {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            throw new Error('Error al añadir el producto');
        }

        const data = await response.json();
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: data.message,
                confirmButtonText: 'Aceptar'
            }).then(() => {
                window.location.href = `../productos/list_producto.php?idcategoria=${categoriaId}`;
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al agregar el producto',
                confirmButtonText: 'Aceptar'
            });
        }
    } catch (error) {
        console.error('Error al añadir el producto:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al procesar la solicitud',
            confirmButtonText: 'Aceptar'
        });
    }
}
