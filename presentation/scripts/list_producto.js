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
        displayProductos(productos, categoriaId);
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

function displayProductos(productos, categoriaId) {
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
        editButton.addEventListener('click', () => {
            editProducto(producto.ID_producto, categoriaId);
        });

        const deleteButton = document.createElement('button');
        deleteButton.textContent = 'Eliminar';
        deleteButton.classList.add('bg-red-500', 'text-white', 'px-4', 'py-2', 'rounded-md', 'shadow-sm', 'hover:bg-red-700', 'focus:outline-none', 'focus:ring-2', 'focus:ring-offset-2', 'focus:ring-red-500');
        deleteButton.addEventListener('click', () => {
            confirmDeleteProducto(producto.ID_producto);
        });

        productCard.appendChild(img);
        productCard.appendChild(title);
        productCard.appendChild(description);
        productCard.appendChild(price);
        productCard.appendChild(stock);
        productCard.appendChild(expirationDate);
        productCard.appendChild(editButton);
        productCard.appendChild(deleteButton);

        productosContainer.appendChild(productCard);
    });
}

function editProducto(idProducto, categoriaId) {
    window.location.href = `editar_producto.php?id_producto=${idProducto}&categoriaId=${categoriaId}`;
}

function confirmDeleteProducto(idProducto) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede deshacer.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminarlo'
    }).then((result) => {
        if (result.isConfirmed) {
            deleteProducto(idProducto);
        }
    });
}

async function deleteProducto(idProducto) {
    try {
        const response = await fetch(`http://refactorizar_proyecto.test/businessLogic/swProducto.php?id=${idProducto}`, {
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
                window.location.reload();
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
        console.error('Error al eliminar el producto:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al eliminar el producto',
            confirmButtonText: 'Aceptar'
        });
    }
}

function filterProductos() {
    const searchCombo = document.getElementById('search-combo').value;
    const searchInput = document.getElementById('search-input').value.trim().toLowerCase();
    const errorMessage = document.getElementById('error-message');
    errorMessage.classList.add('hidden');
    
    let isValid = true;

    switch (searchCombo) {
        case 'name-description':
            isValid = /^[a-zA-Z\s]*$/.test(searchInput);
            break;
        case 'price':
            isValid = /^\d*\.?\d*$/.test(searchInput);
            break;
        case 'stock':
            isValid = /^\d*$/.test(searchInput);
            break;
        case 'expiration':
            isValid = /^\d{4}-\d{2}-\d{2}$/.test(searchInput);
            break;
        default:
            isValid = true;
    }

    if (!isValid) {
        errorMessage.textContent = 'Entrada inválida para el tipo de búsqueda seleccionado.';
        errorMessage.classList.remove('hidden');
        return;
    }

    const productosContainer = document.getElementById('productos-container');
    const productCards = productosContainer.getElementsByClassName('product-card');

    for (let productCard of productCards) {
        const nombre = productCard.dataset.nombre.toLowerCase();
        const descripcion = productCard.dataset.descripcion.toLowerCase();
        const precio = productCard.dataset.precio.toLowerCase();
        const stock = productCard.dataset.stock.toLowerCase();
        const fecha = productCard.dataset.fecha.toLowerCase();

        let isMatch = false;

        switch (searchCombo) {
            case 'name-description':
                isMatch = nombre.includes(searchInput) || descripcion.includes(searchInput);
                break;
            case 'price':
                isMatch = precio.includes(searchInput);
                break;
            case 'stock':
                isMatch = stock.includes(searchInput);
                break;
            case 'expiration':
                isMatch = fecha.includes(searchInput);
                break;
            default:
                isMatch = true;
        }

        if (isMatch) {
            productCard.classList.remove('hidden');
        } else {
            productCard.classList.add('hidden');
        }
    }
}
