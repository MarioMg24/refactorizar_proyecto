document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const idProducto = urlParams.get('id_producto');
    const categoriaId = urlParams.get('categoriaId');

    if (idProducto && categoriaId) {
        cargarDatosProducto(idProducto, categoriaId);
        cargarCategorias();
    } else {
        console.error('No se proporcionó el ID del producto o la categoría');
    }

    const formEditarProducto = document.getElementById('form-editar-producto');
    formEditarProducto.addEventListener('submit', (event) => {
        event.preventDefault();
        editarProducto(idProducto);
    });
});

async function cargarDatosProducto(idProducto, categoriaId) {
    try {
        const response = await fetch(`http://refactorizar_proyecto.test/businessLogic/swProducto.php?categoriaId=${categoriaId}&id_producto=${idProducto}`);
        if (!response.ok) {
            throw new Error('Error al obtener el producto');
        }
        const producto = await response.json();
        llenarFormulario(producto);
    } catch (error) {
        console.error('Error al cargar los datos del producto:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al cargar los datos del producto',
            confirmButtonText: 'Aceptar'
        });
    }
}

function llenarFormulario(producto) {
    document.getElementById('id_producto').value = producto.ID_producto;
    document.getElementById('nombre').value = producto.Nombre_producto;
    document.getElementById('descripcion').value = producto.Descripcion;
    document.getElementById('precio').value = producto.Precio;
    document.getElementById('cantidad').value = producto.Cantidad_disponible;
    document.getElementById('fecha_caducidad').value = producto.Fecha_caducidad ? producto.Fecha_caducidad : '';

    // Asegúrate de que el valor seleccionado en el <select> sea la categoría actual del producto
    document.getElementById('categoria').value = producto.ID_categoria;

    // Puedes manejar la carga de la imagen si lo necesitas
}

async function cargarCategorias() {
    try {
        const response = await fetch('http://refactorizar_proyecto.test/businessLogic/swCategoria.php');
        if (!response.ok) {
            throw new Error('Error al obtener las categorías');
        }
        const categorias = await response.json();
        llenarSelectCategorias(categorias);
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

function llenarSelectCategorias(categorias) {
    const selectCategoria = document.getElementById('categoria');
    selectCategoria.innerHTML = '';

    categorias.forEach(categoria => {
        const option = document.createElement('option');
        option.value = categoria.ID_categoria;
        option.textContent = categoria.Nombre_categoria;
        selectCategoria.appendChild(option);
    });
}

async function editarProducto(idProducto) {
    const formEditarProducto = document.getElementById('form-editar-producto');
    const formData = new FormData(formEditarProducto);

    try {
        const response = await fetch(`http://refactorizar_proyecto.test/businessLogic/swProducto.php?id_producto=${idProducto}`, {
            method: 'PUT',
            body: formData
        });

        const result = await response.json();
        if (result.success) {
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: result.message,
                confirmButtonText: 'Aceptar'
            }).then(() => {
                window.location.href = 'listado_productos.php'; // Redirigir al listado de productos después de editar
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
        console.error('Error al editar el producto:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al editar el producto',
            confirmButtonText: 'Aceptar'
        });
    }
}
