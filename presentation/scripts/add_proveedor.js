// En add_proveedor.js

document.addEventListener('DOMContentLoaded', () => {
    cargarProductos();
});

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
    const productosContainer = document.getElementById('productos-checkbox-list');
    productosContainer.innerHTML = ''; // Limpiar el contenedor

    // Crear un contenedor flex para las columnas
    const columnContainer = document.createElement('div');
    columnContainer.className = 'flex flex-wrap -mx-2'; // Flex container con margen negativo para el gutter

    // Dividir los productos en dos arrays
    const mitad = Math.ceil(productos.length / 2);
    const columnaIzquierda = productos.slice(0, mitad);
    const columnaDerecha = productos.slice(mitad);

    // Función para crear una columna
    function crearColumna(productos) {
        const columna = document.createElement('div');
        columna.className = 'w-1/2 px-2'; // 50% de ancho con padding horizontal

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

    // Crear y añadir las columnas
    columnContainer.appendChild(crearColumna(columnaIzquierda));
    columnContainer.appendChild(crearColumna(columnaDerecha));

    // Añadir el contenedor de columnas al contenedor principal
    productosContainer.appendChild(columnContainer);
}
document.getElementById('form-agregar-proveedor').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const nombreProveedor = document.getElementById('nombre').value;
    const contactoProveedor = document.getElementById('contacto').value;
    const terminosNegociacion = document.getElementById('terminos').value;
    
    const productosSeleccionados = Array.from(document.querySelectorAll('input[name="productos[]"]:checked')).map(cb => cb.value);

    try {
        const response = await fetch('http://refactorizar_proyecto.test/businessLogic/swProveedor.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                nombreProveedor,
                contactoProveedor,
                terminosNegociacion,
                productosSeleccionados
            }),
        });

        if (!response.ok) {
            throw new Error('Error al añadir el proveedor');
        }

        const result = await response.json();
        if (result.success) {
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: 'Proveedor añadido correctamente',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                window.location.href = 'list_proveedor.php';
            });
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al añadir el proveedor',
            confirmButtonText: 'Aceptar'
        });
    }
});