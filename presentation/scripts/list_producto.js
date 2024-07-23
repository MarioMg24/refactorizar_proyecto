document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const categoriaId = urlParams.get('idcategoria');
    
    if (categoriaId) {
        loadProductos(categoriaId);
    } else {
        console.error('No se proporcionó el ID de la categoría');
    }

    // Agregar event listeners para los campos de búsqueda
    document.getElementById('search-input').addEventListener('input', filterProductos);
    document.getElementById('search-combo').addEventListener('change', filterProductos);
});

let productosGlobal = [];

async function loadProductos(categoriaId) {
    try {
        const response = await fetch(`http://refactorizar_proyecto.test/businessLogic/swProducto.php?categoriaId=${categoriaId}`);
        if (!response.ok) {
            throw new Error('Error al obtener los productos');
        }

        const productos = await response.json();
        productosGlobal = productos;  // Guardar productos globalmente para filtrado
        displayProductos(productos);
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

function displayProductos(productos) {
    const productosContainer = document.getElementById('productos-container');
    productosContainer.innerHTML = '';

    productos.forEach(producto => {
        const productCard = document.createElement('div');
        productCard.classList.add('bg-white', 'shadow-md', 'rounded-lg', 'p-4', 'mb-4', 'product-card');
        productCard.dataset.nombre = producto.Nombre_producto;
        productCard.dataset.descripcion = producto.Descripcion;
        productCard.dataset.precio = producto.Precio;
        productCard.dataset.stock = producto.Cantidad_disponible;
        productCard.dataset.fecha = producto.Fecha_caducidad ? producto.Fecha_caducidad : 'Sin fecha';

        const img = document.createElement('img');
        img.src = producto.Imagen_producto;
        img.alt = producto.Nombre_producto;
        img.classList.add('w-full', 'h-32', 'object-cover', 'mb-4');

        const title = document.createElement('h3');
        title.classList.add('text-xl', 'font-bold', 'mb-2', 'text-gray-800');
        title.textContent = producto.Nombre_producto;

        const description = document.createElement('p');
        description.classList.add('text-gray-600', 'mb-2');
        description.textContent = producto.Descripcion;

        const price = document.createElement('p');
        price.classList.add('text-green-500', 'mb-2');
        price.textContent = `$${producto.Precio}`;

        const stock = document.createElement('p');
        stock.classList.add('text-gray-500', 'mb-2');
        stock.textContent = `Stock: ${producto.Cantidad_disponible}`;

        const expirationDate = document.createElement('p');
        expirationDate.classList.add('text-gray-900', 'mb-2');
        expirationDate.textContent = `Fecha de Caducidad: ${producto.Fecha_caducidad ? producto.Fecha_caducidad : 'Sin fecha'}`;

        const editButton = document.createElement('button');
        editButton.textContent = 'Editar';
        editButton.classList.add('bg-blue-500', 'text-white', 'px-4', 'py-2', 'rounded-md', 'shadow-sm', 'hover:bg-blue-700', 'focus:outline-none', 'focus:ring-2', 'focus:ring-offset-2', 'focus:ring-blue-500', 'mr-2');
        editButton.style.display = isAdmin ? 'inline-block' : 'none';  // Mostrar solo si es administrador
        editButton.addEventListener('click', () => {
            editProducto(producto.ID_producto, categoriaId);
        });

        const deleteButton = document.createElement('button');
        deleteButton.textContent = 'Eliminar';
        deleteButton.classList.add('bg-red-500', 'text-white', 'px-4', 'py-2', 'rounded-md', 'shadow-sm', 'hover:bg-red-700', 'focus:outline-none', 'focus:ring-2', 'focus:ring-offset-2', 'focus:ring-red-500');
        deleteButton.style.display = isAdmin ? 'inline-block' : 'none';  // Mostrar solo si es administrador
        deleteButton.addEventListener('click', () => {
            confirmDeleteProducto(producto.ID_producto);
        });

        const addToCartButton = document.createElement('button');
        addToCartButton.textContent = 'Agregar al Carrito';
        addToCartButton.classList.add('bg-green-500', 'text-white', 'px-4', 'py-2', 'rounded-md', 'shadow-sm', 'hover:bg-green-700', 'focus:outline-none', 'focus:ring-2', 'focus:ring-offset-2', 'focus:ring-green-500');
        addToCartButton.style.display = isAdmin ? 'none' : 'inline-block';  // Mostrar solo si no es administrador
        addToCartButton.addEventListener('click', () => {
            // Lógica para agregar al carrito
            Swal.fire({
                icon: 'info',
                title: 'Agregar al Carrito',
                text: 'Esta funcionalidad no está implementada aún.',
                confirmButtonText: 'Aceptar'
            });
        });

        productCard.appendChild(img);
        productCard.appendChild(title);
        productCard.appendChild(description);
        productCard.appendChild(price);
        productCard.appendChild(stock);
        productCard.appendChild(expirationDate);
        productCard.appendChild(editButton);
        productCard.appendChild(deleteButton);
        productCard.appendChild(addToCartButton);

        productosContainer.appendChild(productCard);
    });
}

function filterProductos() {
    const searchInput = document.getElementById('search-input').value.toLowerCase();
    const searchCombo = document.getElementById('search-combo').value;

    const filteredProductos = productosGlobal.filter(producto => {
        const searchValue = producto[searchCombo].toString().toLowerCase();
        return searchValue.includes(searchInput);
    });

    displayProductos(filteredProductos);
}

function editProducto(id, categoriaId) {
    // Lógica para editar el producto
    Swal.fire({
        icon: 'info',
        title: 'Editar Producto',
        text: `Funcionalidad de edición para el producto con ID ${id}.`,
        confirmButtonText: 'Aceptar'
    });
}

function confirmDeleteProducto(id) {
    Swal.fire({
        title: 'Confirmar Eliminación',
        text: "¿Está seguro de que desea eliminar este producto?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, Eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            deleteProducto(id);
        }
    });
}

async function deleteProducto(id) {
    try {
        const response = await fetch(`http://refactorizar_proyecto.test/businessLogic/swProducto.php?action=delete&id=${id}`, {
            method: 'DELETE'
        });
        if (!response.ok) {
            throw new Error('Error al eliminar el producto');
        }

        Swal.fire({
            icon: 'success',
            title: 'Eliminado',
            text: 'El producto ha sido eliminado.',
            confirmButtonText: 'Aceptar'
        }).then(() => {
            const categoriaId = new URLSearchParams(window.location.search).get('idcategoria');
            if (categoriaId) {
                loadProductos(categoriaId);
            }
        });
    } catch (error) {
        console.error('Error al eliminar el producto:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al eliminar el producto.',
            confirmButtonText: 'Aceptar'
        });
    }
}
