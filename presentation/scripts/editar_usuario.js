document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const idUsuario = urlParams.get('id_usuario');

    if (idUsuario) {
        cargarDatosUsuario(idUsuario);
    } else {
        console.error('No se encontró el ID del usuario en la URL');
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se encontró el ID del usuario en la URL',
            confirmButtonText: 'Aceptar'
        });
    }

    const formEditarUsuario = document.getElementById('form-editar-usuario');
    formEditarUsuario.addEventListener('submit', (e) => {
        e.preventDefault();
        guardarCambiosUsuario();
    });
});

async function cargarDatosUsuario(idUsuario) {
    try {
        const response = await fetch(`http://refactorizar_proyecto.test/businessLogic/swUsuario.php?id_usuario=${idUsuario}`);
        if (!response.ok) {
            throw new Error('Error al obtener los datos del usuario');
        }

        const data = await response.json();

        if (data && data.ID_usuario) {
            const { ID_usuario, Nombre, Apellido, Correo_electronico,Contraseña, Telefono, Direccion, Perfil } = data;

            document.getElementById('id_usuario').value = ID_usuario;
            document.getElementById('nombre').value = Nombre;
            document.getElementById('apellido').value = Apellido;
            document.getElementById('correo_electronico').value = Correo_electronico;
            document.getElementById('contrasena').value = Contraseña;
            document.getElementById('telefono').value = Telefono;
            document.getElementById('direccion').value = Direccion;
            document.getElementById('perfil').value = Perfil;
        } else {
            throw new Error('La respuesta del servidor no contiene los datos esperados');
        }
    } catch (error) {
        console.error('Error al cargar los datos del usuario:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al cargar los datos del usuario',
            confirmButtonText: 'Aceptar'
        });
    }
}

async function guardarCambiosUsuario() {
    const idUsuario = document.getElementById('id_usuario').value;
    const nombre = document.getElementById('nombre').value;
    const apellido = document.getElementById('apellido').value;
    const correoElectronico = document.getElementById('correo_electronico').value;
    const contrasena = document.getElementById('contrasena').value;
    const telefono = document.getElementById('telefono').value;
    const direccion = document.getElementById('direccion').value;
    const perfil = document.getElementById('perfil').value;

    const data = {
        idUsuario: idUsuario,
        nombre: nombre,
        apellido: apellido,
        correo_electronico: correoElectronico,
        contrasena: contrasena,
        telefono: telefono,
        direccion: direccion,
        perfil: perfil
    };

    try {
        const response = await fetch('http://refactorizar_proyecto.test/businessLogic/swUsuario.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            throw new Error('Error al actualizar el usuario');
        }

        const result = await response.json();

        if (result.success) {
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: result.message,
                confirmButtonText: 'Aceptar'
            }).then(() => {
                window.location.href = '../usuarios/list_usuario.php';
            });
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        console.error('Error al guardar cambios en el usuario:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al guardar cambios en el usuario',
            confirmButtonText: 'Aceptar'
        });
    }
}