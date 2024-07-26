document.addEventListener('DOMContentLoaded', () => {
    loadPedidos();
});

async function loadPedidos() {
    try {
        const response = await fetch('http://refactorizar_proyecto.test/businessLogic/swPedido.php');
        if (!response.ok) {
            throw new Error('Error al obtener los pedidos');
        }

        const pedidos = await response.json();
        displayPedidos(pedidos);
    } catch (error) {
        console.error('Error al cargar los pedidos:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al cargar los pedidos',
            confirmButtonText: 'Aceptar'
        });
    }
}

function displayPedidos(pedidos) {
    const pedidosContainer = document.getElementById('pedidos-container');
    pedidosContainer.innerHTML = '';

    pedidos.forEach(pedido => {
        const pedidoCard = document.createElement('div');
        pedidoCard.classList.add('bg-white', 'shadow-md', 'rounded-lg', 'p-4', 'mb-4', 'pedido-card');

        const title = document.createElement('h3');
        title.classList.add('text-xl', 'font-bold', 'mb-2', 'text-gray-800');
        title.textContent = `Pedido #${pedido.ID_pedido}`;

        const total = document.createElement('p');
        total.classList.add('text-green-500', 'mb-2');
        total.textContent = `Total: $${pedido.Total}`;

        const estado = document.createElement('p');
        estado.classList.add('text-gray-600', 'mb-2');
        estado.textContent = `Estado: ${pedido.Estado}`;

        const fechaPedido = document.createElement('p');
        fechaPedido.classList.add('text-gray-500', 'mb-2');
        fechaPedido.textContent = `Fecha: ${new Date(pedido.Fecha_pedido).toLocaleDateString()}`;

        const cancelButton = document.createElement('button');
        cancelButton.textContent = 'Cancelar Pedido';
        cancelButton.classList.add('bg-red-500', 'text-white', 'px-4', 'py-2', 'rounded-md', 'shadow-sm', 'hover:bg-red-700', 'focus:outline-none', 'focus:ring-2', 'focus:ring-offset-2', 'focus:ring-red-500');
        cancelButton.addEventListener('click', () => {
            cancelarPedido(pedido.ID_pedido);
        });

        pedidoCard.appendChild(title);
        pedidoCard.appendChild(total);
        pedidoCard.appendChild(estado);
        pedidoCard.appendChild(fechaPedido);
        if (pedido.Estado.toLowerCase() === 'pendiente') {
            pedidoCard.appendChild(cancelButton);
        }

        pedidosContainer.appendChild(pedidoCard);
    });
}

async function cancelarPedido(idPedido) {
    try {
        const result = await Swal.fire({
            title: 'Confirmar Cancelación',
            text: "¿Está seguro de que desea cancelar este pedido?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, cancelar',
            cancelButtonText: 'No, mantener'
        });

        if (result.isConfirmed) {
            const response = await fetch('http://refactorizar_proyecto.test/businessLogic/swPedido.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'cancelarPedido',
                    idPedido: idPedido
                })
            });

            const responseData = await response.json();
            if (responseData.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Cancelado',
                    text: 'El pedido ha sido cancelado.',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    loadPedidos(); // Recargar los pedidos
                });
            } else {
                throw new Error(responseData.message);
            }
        }
    } catch (error) {
        console.error('Error al cancelar el pedido:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al cancelar el pedido.',
            confirmButtonText: 'Aceptar'
        });
    }
}
