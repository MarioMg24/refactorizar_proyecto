document.addEventListener('DOMContentLoaded', () => {
    loadUsuarios();
});

async function loadUsuarios() {
    try {
        const response = await fetch('http://refactorizar_proyecto.test/businessLogic/swUsuario.php');
        if (!response.ok) {
            throw new Error('Error al obtener los usuarios');
        }

        const usuarios = await response.json();
        displayUsuarios(usuarios);
    } catch (error) {
        console.error('Error al cargar los usuarios:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al cargar los usuarios',
            confirmButtonText: 'Aceptar'
        });
    }
}

function displayUsuarios(usuarios) {
    const usuariosBody = document.getElementById('usuarios-body');
    usuariosBody.innerHTML = '';

    usuarios.forEach(usuario => {
        const row = document.createElement('tr');

        const idCell = document.createElement('td');
        idCell.textContent = usuario.ID_usuario;
        idCell.classList.add('px-6', 'py-4', 'whitespace-no-wrap', 'border-b', 'border-gray-300', 'text-sm', 'leading-5', 'font-medium', 'text-gray-700');
        
        const nombreCell = document.createElement('td');
        nombreCell.textContent = `${usuario.Nombre} ${usuario.Apellido}`;
        nombreCell.classList.add('px-6', 'py-4', 'whitespace-no-wrap', 'border-b', 'border-gray-300', 'text-sm', 'leading-5', 'text-gray-700');

        const correoCell = document.createElement('td');
        correoCell.textContent = usuario.Correo_electronico;
        correoCell.classList.add('px-6', 'py-4', 'whitespace-no-wrap', 'border-b', 'border-gray-300', 'text-sm', 'leading-5', 'text-gray-700');

        const perfilCell = document.createElement('td');
        perfilCell.textContent = usuario.Perfil;
        perfilCell.classList.add('px-6', 'py-4', 'whitespace-no-wrap', 'border-b', 'border-gray-300', 'text-sm', 'leading-5', 'text-gray-700');

        const accionesCell = document.createElement('td');
        accionesCell.classList.add('px-6', 'py-4', 'whitespace-no-wrap', 'border-b', 'border-gray-300', 'text-sm', 'leading-5', 'font-medium', 'text-gray-700');

        // Solo agregar botones si el usuario es administrador
        if (isAdmin) {
            const editarBtn = createButton('Editar', 'bg-blue-500', () => editUsuario(usuario.ID_usuario));
            const eliminarBtn = createButton('Eliminar', 'bg-red-500', () => confirmDeleteUsuario(usuario.ID_usuario));

            accionesCell.appendChild(editarBtn);
            accionesCell.appendChild(eliminarBtn);
        }

        row.appendChild(idCell);
        row.appendChild(nombreCell);
        row.appendChild(correoCell);
        row.appendChild(perfilCell);
        row.appendChild(accionesCell);

        usuariosBody.appendChild(row);
    });
}

function createButton(text, bgColor, onClick) {
    const button = document.createElement('button');
    button.textContent = text;
    button.classList.add(bgColor, 'text-white', 'px-4', 'py-2', 'rounded-md', 'shadow-sm', 'hover:bg-opacity-80', 'focus:outline-none', 'focus:ring-2', 'focus:ring-offset-2', 'focus:ring-opacity-50', 'mr-2');
    button.addEventListener('click', (event) => {
        event.stopPropagation();
        onClick();
    });
    return button;
}

function editUsuario(idUsuario) {
    window.location.href = `editar_usuario.php?id_usuario=${idUsuario}`;
}

function confirmDeleteUsuario(idUsuario) {
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
            deleteUsuario(idUsuario);
        }
    });
}

async function deleteUsuario(idUsuario) {
    try {
        const response = await fetch(`http://refactorizar_proyecto.test/businessLogic/swUsuario.php?id=${idUsuario}`, {
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
        console.error('Error al eliminar el usuario:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al eliminar el usuario',
            confirmButtonText: 'Aceptar'
        });
    }
}
