document.addEventListener('DOMContentLoaded', () => {
    loadCarrito();
    setupOrderButton();
});

async function loadCarrito() {
    try {
        const response = await fetch('http://refactorizar_proyecto.test/businessLogic/swCarrito.php');
        if (!response.ok) {
            throw new Error('Error al obtener los productos del carrito');
        }

        const productos = await response.json();
        displayCarrito(productos);
    } catch (error) {
        console.error('Error al cargar el carrito:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al cargar los productos del carrito',
            confirmButtonText: 'Aceptar'
        });
    }
}

function displayCarrito(productos) {
    const carritoContainer = document.getElementById('carrito-container');
    carritoContainer.innerHTML = '';
    let subtotalAmount = 0;

    productos.forEach(producto => {
        const productCard = document.createElement('div');
        productCard.classList.add('bg-white', 'shadow-md', 'rounded-lg', 'p-4', 'mb-4', 'product-card');

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

        const quantity = document.createElement('p');
        quantity.classList.add('text-gray-500', 'mb-2');
        quantity.textContent = `Cantidad: ${producto.Cantidad}`;

        const changeQuantityButton = document.createElement('button');
        changeQuantityButton.textContent = 'Cambiar Cantidad';
        changeQuantityButton.classList.add('bg-yellow-500', 'text-white', 'px-4', 'py-2', 'rounded-md', 'shadow-sm', 'hover:bg-yellow-700', 'focus:outline-none', 'focus:ring-2', 'focus:ring-offset-2', 'focus:ring-yellow-500', 'mr-2');
        changeQuantityButton.addEventListener('click', () => {
            showQuantityChangeModal(producto.ID_producto, producto.Cantidad);
        });

        const removeButton = document.createElement('button');
        removeButton.textContent = 'Quitar del Carrito';
        removeButton.classList.add('bg-red-500', 'text-white', 'px-4', 'py-2', 'rounded-md', 'shadow-sm', 'hover:bg-red-700', 'focus:outline-none', 'focus:ring-2', 'focus:ring-offset-2', 'focus:ring-red-500');
        removeButton.addEventListener('click', () => {
            quitarProductoDelCarrito(producto.ID_producto);
        });

        productCard.appendChild(img);
        productCard.appendChild(title);
        productCard.appendChild(description);
        productCard.appendChild(price);
        productCard.appendChild(quantity);
        productCard.appendChild(changeQuantityButton);
        productCard.appendChild(removeButton);

        carritoContainer.appendChild(productCard);

        subtotalAmount += producto.Precio * producto.Cantidad;
    });

    const totalAmount = subtotalAmount; // Add other charges if necessary
    document.getElementById('subtotal-amount').textContent = `$${subtotalAmount.toFixed(2)}`;
    document.getElementById('total-amount').textContent = `$${totalAmount.toFixed(2)}`;
}

function showQuantityChangeModal(idProducto, cantidadActual) {
    Swal.fire({
        title: 'Cambiar Cantidad',
        input: 'number',
        inputValue: cantidadActual,
        showCancelButton: true,
        confirmButtonText: 'Actualizar',
        cancelButtonText: 'Cancelar',
        inputValidator: (value) => {
            if (!value || value <= 0) {
                return 'Por favor ingrese una cantidad válida';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            actualizarCantidadProducto(idProducto, result.value);
        }
    });
}

async function actualizarCantidadProducto(idProducto, nuevaCantidad) {
    try {
        const response = await fetch('http://refactorizar_proyecto.test/businessLogic/swCarrito.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                action: 'actualizarCantidad',
                idProducto: idProducto,
                nuevaCantidad: nuevaCantidad
            })
        });

        const result = await response.json();
        if (result.success) {
            Swal.fire({
                icon: 'success',
                title: 'Cantidad Actualizada',
                text: 'La cantidad del producto ha sido actualizada.',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                loadCarrito();
            });
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        console.error('Error al actualizar la cantidad del producto:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al actualizar la cantidad del producto.',
            confirmButtonText: 'Aceptar'
        });
    }
}

async function quitarProductoDelCarrito(idProducto) {
    try {
        const response = await fetch(`http://refactorizar_proyecto.test/businessLogic/swCarrito.php?idProducto=${idProducto}`, {
            method: 'DELETE'
        });
        if (!response.ok) {
            throw new Error('Error al quitar el producto del carrito');
        }

        Swal.fire({
            icon: 'success',
            title: 'Eliminado',
            text: 'El producto ha sido eliminado del carrito.',
            confirmButtonText: 'Aceptar'
        }).then(() => {
            loadCarrito();
        });
    } catch (error) {
        console.error('Error al eliminar el producto del carrito:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al eliminar el producto del carrito.',
            confirmButtonText: 'Aceptar'
        });
    }
}

function setupOrderButton() {
    const orderButton = document.createElement('button');
    orderButton.textContent = 'Realizar Pedido';
    orderButton.classList.add('bg-green-500', 'text-white', 'px-4', 'py-2', 'rounded-md', 'shadow-sm', 'hover:bg-green-700', 'focus:outline-none', 'focus:ring-2', 'focus:ring-offset-2', 'focus:ring-green-500', 'mt-4');
    orderButton.addEventListener('click', realizarPedido);
    
    const totalContainer = document.getElementById('total-container');
    totalContainer.appendChild(orderButton);
}

async function realizarPedido() {
    try {
        const totalAmount = parseFloat(document.getElementById('total-amount').textContent.replace('$', ''));
        const productos = await fetchCarritoProductos();

        const response = await fetch('http://refactorizar_proyecto.test/businessLogic/swPedido.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                action: 'realizarPedido',
                totalAmount: totalAmount,
                productos: productos
            })
        });

        const result = await response.json();
        if (result.success) {
            Swal.fire({
                icon: 'success',
                title: 'Pedido Realizado',
                text: `Su pedido ha sido realizado con éxito. Número de pedido: ${result.idPedido}`,
                confirmButtonText: 'Aceptar'
            }).then(() => {
                loadCarrito(); // Recargar el carrito (que ahora estará vacío)
            });
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        console.error('Error al realizar el pedido:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al realizar el pedido. Por favor, inténtelo de nuevo.',
            confirmButtonText: 'Aceptar'
        });
    }
}

async function fetchCarritoProductos() {
    try {
        const response = await fetch('http://refactorizar_proyecto.test/businessLogic/swCarrito.php');
        if (!response.ok) {
            throw new Error('Error al obtener los productos del carrito');
        }

        const productos = await response.json();
        return productos.map(producto => ({
            idProducto: producto.ID_producto,
            cantidad: producto.Cantidad,
            precio: producto.Precio
        }));
    } catch (error) {
        console.error('Error al obtener los productos del carrito:', error);
        return [];
    }
}
