document.addEventListener('DOMContentLoaded', () => {
    cargarPerfilUsuario();
});

async function cargarPerfilUsuario() {
    try {
        console.log('Iniciando solicitud de perfil de usuario');
        const response = await fetch('http://refactorizar_proyecto.test/businessLogic/swUsuario.php?perfil=true');
        console.log('Respuesta recibida:', response);

        if (!response.ok) {
            throw new Error(`Error al obtener los datos del perfil. Estado: ${response.status}`);
        }

        const texto = await response.text();
        console.log('Texto de respuesta:', texto);

        let usuario;
        try {
            usuario = JSON.parse(texto);
        } catch (e) {
            console.error('Error al parsear JSON:', e);
            console.error('Texto recibido no es un JSON válido:', texto);
            throw new Error('La respuesta no es un JSON válido');
        }

        console.log('Datos de usuario recibidos:', usuario);
        mostrarPerfilUsuario(usuario);
    } catch (error) {
        console.error('Error al cargar el perfil del usuario:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al cargar el perfil del usuario: ' + error.message,
            confirmButtonText: 'Aceptar'
        });
    }
}

function mostrarPerfilUsuario(usuario) {
    const perfilContainer = document.getElementById('perfil-container');
    perfilContainer.innerHTML = `
        <h3 class="text-xl font-bold mb-4">${usuario.Nombre} ${usuario.Apellido}</h3>
        <p class="mb-2"><strong>Correo electrónico:</strong> ${usuario.Correo_electronico}</p>
        <p class="mb-2"><strong>Teléfono:</strong> ${usuario.Telefono || 'No especificado'}</p>
        <p class="mb-2"><strong>Dirección:</strong> ${usuario.Direccion || 'No especificada'}</p>
        <p class="mb-2"><strong>Perfil:</strong> ${usuario.Perfil}</p>
        <button onclick="editarPerfil()" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Editar Perfil
        </button>
    `;
}

async function editarPerfil() {
    console.log('Editar perfil');
}
