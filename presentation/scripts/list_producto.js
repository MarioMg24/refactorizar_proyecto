document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const categoriaId = urlParams.get('idcategoria');
    
    if (categoriaId) {
        loadProductos(categoriaId);
    } else {
        console.error('No se proporcionó el ID de la categoría');
    }
});

async function loadProductos(categoriaId) {
    try {
        const response = await fetch(`http://refactorizar_proyecto.test/businessLogic/swProducto.php?categoriaId=${categoriaId}`);
        if (!response.ok) {
            throw new Error('Error al obtener los productos');
        }

        const productos = await response.json();
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
        productCard.classList.add('bg-white', 'shadow-md', 'rounded-lg', 'p-4', 'mb-4');

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
            editProducto(producto.ID_producto);
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

function editProducto(idProducto) {
    window.location.href = `editar_producto.php?id=${idProducto}`;
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
