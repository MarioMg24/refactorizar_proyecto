document.addEventListener('DOMContentLoaded', () => {
    loadProveedoresConProductos();
});

async function loadProveedoresConProductos() {
    try {
        const response = await fetch('http://refactorizar_proyecto.test/businessLogic/swProveedor.php?listarProveedoresConProductos=true');
        if (!response.ok) {
            throw new Error('Error al obtener los proveedores con productos');
        }

        const proveedores = await response.json();
        displayProveedores(proveedores);
    } catch (error) {
        console.error('Error al cargar los proveedores:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al cargar los proveedores con productos',
            confirmButtonText: 'Aceptar'
        });
    }
}

function displayProveedores(proveedores) {
    const proveedoresContainer = document.getElementById('proveedores-container');
    proveedoresContainer.innerHTML = '';

    proveedores.forEach(proveedor => {
        const proveedorCard = document.createElement('div');
        proveedorCard.classList.add('bg-white', 'shadow-md', 'rounded-lg', 'p-4', 'mb-4');

        const title = document.createElement('h3');
        title.classList.add('text-xl', 'font-bold', 'mb-2', 'text-gray-800');
        title.textContent = proveedor.Nombre_proveedor;

        const contacto = document.createElement('p');
        contacto.classList.add('text-gray-600', 'mb-2');
        contacto.innerHTML = `<strong>Contacto:</strong> ${proveedor.Contacto}`;

        const terminos = document.createElement('p');
        terminos.classList.add('text-gray-600', 'mb-2');
        terminos.innerHTML = `<strong>Términos de negociación:</strong> ${proveedor.Terminos_negociacion}`;

        const productosTitle = document.createElement('p');
        productosTitle.classList.add('text-lg', 'font-bold', 'mb-2', 'text-gray-800');
        productosTitle.textContent = 'Productos:';

        const productosList = document.createElement('ul');
        productosList.classList.add('list-disc', 'pl-5');

        let hasProductos = false;

        proveedor.Productos.forEach(producto => {
            if (producto.Nombre_producto.trim() !== '') {
                const productoItem = document.createElement('li');
                productoItem.classList.add('text-gray-800');
                productoItem.textContent = producto.Nombre_producto;
                productosList.appendChild(productoItem);
                hasProductos = true;
            }
        });

        if (!hasProductos) {
            const noProductos = document.createElement('p');
            noProductos.classList.add('text-gray-600', 'mb-2');
            noProductos.textContent = 'Sin productos';
            productosList.appendChild(noProductos);
        }

        const editButton = document.createElement('button');
        editButton.classList.add('bg-blue-500', 'hover:bg-blue-700', 'text-white', 'font-bold', 'py-2', 'px-4', 'rounded', 'mr-2');
        editButton.textContent = 'Editar';
        editButton.onclick = () => editProveedor(proveedor.ID_proveedor);

        const deleteButton = document.createElement('button');
        deleteButton.classList.add('bg-red-500', 'hover:bg-red-700', 'text-white', 'font-bold', 'py-2', 'px-4', 'rounded', 'mt-2');
        deleteButton.textContent = 'Eliminar';
        deleteButton.onclick = () => deleteProveedor(proveedor.ID_proveedor);

        proveedorCard.appendChild(title);
        proveedorCard.appendChild(contacto);
        proveedorCard.appendChild(terminos);
        proveedorCard.appendChild(productosTitle);
        proveedorCard.appendChild(productosList);
        proveedorCard.appendChild(editButton);
        proveedorCard.appendChild(deleteButton);

        proveedoresContainer.appendChild(proveedorCard);
    });
}

function editProveedor(idProveedor) {
    window.location.href = `editar_proveedor.php?id_proveedor=${idProveedor}`;
}


async function deleteProveedor(idProveedor) {
    try {
        const response = await fetch(`http://refactorizar_proyecto.test/businessLogic/swProveedor.php?id=${idProveedor}`, {
            method: 'DELETE',
        });

        if (!response.ok) {
            throw new Error('Error al eliminar el proveedor');
        }

        const result = await response.json();

        if (result.success) {
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: result.message,
                confirmButtonText: 'Aceptar'
            }).then(() => {
                // Recargar la lista de proveedores
                loadProveedoresConProductos();
            });
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        console.error('Error al eliminar el proveedor:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al eliminar el proveedor',
            confirmButtonText: 'Aceptar'
        });
    }
}