document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById('inicio-sesion-form');
  
    loginForm.addEventListener('submit', async function (event) {
      event.preventDefault();
  
      const correo_electronico = document.getElementById('correo_electronico').value;
      const contrasena = document.getElementById('contrasena').value;
  
      try {
        const response = await fetch('http://refactorizar_proyecto.test/businessLogic/swUsuario.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            correo_electronico: correo_electronico,
            contrasena: contrasena,
            action: 'login' // Especifica que es una acción de inicio de sesión
          })
        });
  
        const result = await response.json();
  
        if (result.success) {
          Swal.fire({
            icon: 'success',
            title: 'Inicio de sesión exitoso',
            showConfirmButton: false,
            timer: 1500
          }).then(() => {
            window.location.href = '../categorias/list_categoria.php';
          });
        } else {
          document.getElementById('error-message').textContent = 'Correo electrónico o contraseña incorrectos.';
          document.getElementById('error-message').classList.remove('hidden');
        }
      } catch (error) {
        console.error('Error:', error);
        document.getElementById('error-message').textContent = 'Ocurrió un error. Por favor, intenta de nuevo.';
        document.getElementById('error-message').classList.remove('hidden');
      }
    });
  });
  