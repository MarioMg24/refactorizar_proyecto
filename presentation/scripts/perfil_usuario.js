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
        <button onclick="cambiarContrasena()" class="mt-4 bg-red-500 text-white px-4 py-2 rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
            Cambiar Contraseña
        </button>
    `;
}


async function cambiarContrasena() {
    const { value: nuevaContrasena } = await Swal.fire({
        title: 'Cambiar Contraseña',
        input: 'password',
        inputLabel: 'Nueva Contraseña',
        inputPlaceholder: 'Ingrese la nueva contraseña',
        inputAttributes: {
            maxlength: 50,
            autocapitalize: 'off',
            autocorrect: 'off'
        },
        showCancelButton: true,
        confirmButtonText: 'Cambiar',
        cancelButtonText: 'Cancelar'
    });

    if (nuevaContrasena) {
        try {
            const response = await fetch('http://refactorizar_proyecto.test/businessLogic/swUsuario.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'changePassword',
                    nueva_contrasena: nuevaContrasena
                })
            });

            const contentType = response.headers.get("content-type");
            if (contentType && contentType.indexOf("application/json") !== -1) {
                const result = await response.json();

                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Contraseña Cambiada',
                        text: result.message,
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        window.location.href = 'login.php';
                    });
                } else {
                    throw new Error(result.message);
                }
            } else {
                const text = await response.text();
                throw new Error('La respuesta no es JSON: ' + text);
            }
        } catch (error) {
            console.error('Error completo:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un error al cambiar la contraseña: ' + error.message,
                confirmButtonText: 'Aceptar'
            });
        }
    }
}