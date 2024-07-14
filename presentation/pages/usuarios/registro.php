<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro de Usuario</title>
  <link rel="stylesheet" href="../../styles/tailwind.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

  <!-- Encabezado -->
  <header class="bg-blue-800 p-4 shadow-md">
    <h1 class="text-white text-center text-3xl">App de Inventario</h1>
  </header>

  <!-- Contenido Principal -->
  <main class="container mx-auto mt-8">
    <h2 class="text-2xl font-bold mb-4 text-gray-800 text-center">Registro de Usuario</h2>

    <div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md">
      <form id="registro-form">
        <div class="mb-4">
          <label for="nombre" class="block text-gray-700">Nombre</label>
          <input type="text" id="nombre" name="nombre" class="w-full px-3 py-2 border rounded-md" required>
        </div>
        <div class="mb-4">
          <label for="apellido" class="block text-gray-700">Apellido</label>
          <input type="text" id="apellido" name="apellido" class="w-full px-3 py-2 border rounded-md" required>
        </div>
        <div class="mb-4">
          <label for="correo_electronico" class="block text-gray-700">Correo Electrónico</label>
          <input type="email" id="correo_electronico" name="correo_electronico" class="w-full px-3 py-2 border rounded-md" required>
        </div>
        <div class="mb-4">
          <label for="contrasena" class="block text-gray-700">Contraseña</label>
          <input type="password" id="contrasena" name="contrasena" class="w-full px-3 py-2 border rounded-md" required>
        </div>
        <div class="mb-4">
          <label for="telefono" class="block text-gray-700">Teléfono</label>
          <input type="text" id="telefono" name="telefono" class="w-full px-3 py-2 border rounded-md">
        </div>
        <div class="mb-4">
          <label for="direccion" class="block text-gray-700">Dirección</label>
          <input type="text" id="direccion" name="direccion" class="w-full px-3 py-2 border rounded-md">
        </div>
        <div class="mb-4">
          <label for="perfil" class="block text-gray-700">Perfil</label>
          <select id="perfil" name="perfil" class="w-full px-3 py-2 border rounded-md">
            <option value="">Seleccione un perfil</option>
            <option value="Administrador">Administrador</option>
            <option value="Empleado">Empleado</option>
          </select>
        </div>
        <div id="error-message" class="text-red-500 mb-4 hidden"></div>
        <div id="success-message" class="text-green-500 mb-4 hidden"></div>
        <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Registrarse</button>
      </form>
    </div>
  </main>

  <!-- Pie de Página -->
  <footer class="bg-blue-800 p-4 text-center text-white">
    <p>Espoch todos los derechos reservados © 2024</p>
  </footer>
  <script src="../../scripts/registro.js"></script>
</body>
</html>
