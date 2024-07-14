document.addEventListener('DOMContentLoaded', function () {
    const registroForm = document.getElementById('registro-form');
  
    registroForm.addEventListener('submit', async function (event) {
      event.preventDefault();
  
      const nombre = document.getElementById('nombre').value.trim();
      const apellido = document.getElementById('apellido').value.trim();
      const correo_electronico = document.getElementById('correo_electronico').value.trim();
      const contrasena = document.getElementById('contrasena').value.trim();
      const telefono = document.getElementById('telefono').value.trim();
      const direccion = document.getElementById('direccion').value.trim();
      const perfil = document.getElementById('perfil').value.trim();
  
      // Validaciones
      if (!nombre || !apellido || !correo_electronico || !contrasena || !perfil) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Por favor, complete todos los campos obligatorios.',
        });
        return;
      }
  
      if (!validateEmail(correo_electronico)) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Por favor, ingrese un correo electrónico válido.',
        });
        return;
      }
  
      try {
        const response = await fetch('http://refactorizar_proyecto.test/businessLogic/swUsuario.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            nombre: nombre,
            apellido: apellido,
            correo_electronico: correo_electronico,
            contrasena: contrasena,
            telefono: telefono,
            direccion: direccion,
            perfil: perfil,
            action: 'register' // Especifica que es una acción de registro
          })
        });
  
        const result = await response.json();
  
        if (result.success) {
          Swal.fire({
            icon: 'success',
            title: 'Registro exitoso',
            showConfirmButton: false,
            timer: 1000
          }).then(() => {
            window.location.href = 'login.php';
          });
        } else {
          document.getElementById('error-message').textContent = result.message || 'Error al registrar usuario.';
          document.getElementById('error-message').classList.remove('hidden');
        }
      } catch (error) {
        console.error('Error:', error);
        document.getElementById('error-message').textContent = 'Ocurrió un error. Por favor, intenta de nuevo.';
        document.getElementById('error-message').classList.remove('hidden');
      }
    });
  
    function validateEmail(email) {
      const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return re.test(email);
    }
  });
  