document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const idProveedor = urlParams.get('id_proveedor');

    if (idProveedor) {
        cargarDatosProveedor(idProveedor);
    } else {
        console.error('No se encontró el ID del proveedor en la URL');
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se encontró el ID del proveedor en la URL',
            confirmButtonText: 'Aceptar'
        });
    }

    const formEditarProveedor = document.getElementById('form-editar-proveedor');
    formEditarProveedor.addEventListener('submit', (e) => {
        e.preventDefault();
        guardarCambiosProveedor();
    });
});

async function cargarDatosProveedor(idProveedor) {
    try {
        const response = await fetch(`http://refactorizar_proyecto.test/businessLogic/swProveedor.php?id_proveedor=${idProveedor}`);
        if (!response.ok) {
            throw new Error('Error al obtener los datos del proveedor');
        }

        const data = await response.json();

        if (data && data.ID_proveedor) {
            const { ID_proveedor, Nombre_proveedor, Contacto, Terminos_negociacion, Productos } = data;

            document.getElementById('id_proveedor').value = ID_proveedor;
            document.getElementById('nombre').value = Nombre_proveedor;
            document.getElementById('contacto').value = Contacto;
            document.getElementById('terminos').value = Terminos_negociacion;

            await cargarProductos();
            marcarProductosSeleccionados(Productos);
        } else {
            throw new Error('La respuesta del servidor no contiene los datos esperados');
        }
    } catch (error) {
        console.error('Error al cargar los datos del proveedor:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al cargar los datos del proveedor',
            confirmButtonText: 'Aceptar'
        });
    }
}

async function guardarCambiosProveedor() {
    const idProveedor = document.getElementById('id_proveedor').value;
    const nombreProveedor = document.getElementById('nombre').value;
    const contactoProveedor = document.getElementById('contacto').value;
    const terminosNegociacion = document.getElementById('terminos').value;

    const productosSeleccionados = obtenerProductosSeleccionados();

    const data = {
        idProveedor: idProveedor,
        nombreProveedor: nombreProveedor,
        contactoProveedor: contactoProveedor,
        terminosNegociacion: terminosNegociacion,
        productosSeleccionados: productosSeleccionados
    };

    try {
        const response = await fetch('http://refactorizar_proyecto.test/businessLogic/swProveedor.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            throw new Error('Error al actualizar el proveedor');
        }

        const result = await response.json();

        if (result.success) {
            const message = result.noChanges ? 'No se realizaron cambios' : result.message;

            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: message,
                confirmButtonText: 'Aceptar'
            }).then(() => {
                window.location.href = '../proveedores/list_proveedor.php';
            });
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        console.error('Error al guardar cambios en el proveedor:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al guardar cambios en el proveedor',
            confirmButtonText: 'Aceptar'
        });
    }
}

async function cargarProductos() {
    try {
        const response = await fetch('http://refactorizar_proyecto.test/businessLogic/swProducto.php?getAllProducts=true');
        if (!response.ok) {
            throw new Error('Error al obtener los productos');
        }
        const productos = await response.json();
        mostrarProductos(productos);
    } catch (error) {
        console.error('Error al cargar los productos:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al cargar los productos',
            confirmButtonText: 'Aceptar'
        });
    }
}

function mostrarProductos(productos) {
    const productosContainer = document.getElementById('productos-list');
    productosContainer.innerHTML = ''; 

    const columnContainer = document.createElement('div');
    columnContainer.className = 'flex flex-wrap -mx-2'; 

    const mitad = Math.ceil(productos.length / 2);
    const columnaIzquierda = productos.slice(0, mitad);
    const columnaDerecha = productos.slice(mitad);

    function crearColumna(productos) {
        const columna = document.createElement('div');
        columna.className = 'w-1/2 px-2'; 

        productos.forEach(producto => {
            const div = document.createElement('div');
            div.className = 'flex items-center mb-2';

            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.id = `producto-${producto.ID_producto}`;
            checkbox.value = producto.ID_producto;
            checkbox.name = 'productos[]';
            checkbox.className = 'form-checkbox h-5 w-5 text-blue-600';

            const label = document.createElement('label');
            label.htmlFor = `producto-${producto.ID_producto}`;
            label.textContent = producto.Nombre_producto;
            label.className = 'ml-2 text-sm text-gray-700';

            div.appendChild(checkbox);
            div.appendChild(label);
            columna.appendChild(div);
        });

        return columna;
    }

    columnContainer.appendChild(crearColumna(columnaIzquierda));
    columnContainer.appendChild(crearColumna(columnaDerecha));

    productosContainer.appendChild(columnContainer);
}

function marcarProductosSeleccionados(productos) {
    const checkboxes = document.querySelectorAll('input[name="productos[]"]');
    productos.forEach(producto => {
        const checkbox = document.getElementById(`producto-${producto.ID_producto}`);
        if (checkbox) {
            checkbox.checked = true;
        }
    });
}

function obtenerProductosSeleccionados() {
    const checkboxes = document.querySelectorAll('input[name="productos[]"]:checked');
    return Array.from(checkboxes).map(checkbox => checkbox.value);
}
