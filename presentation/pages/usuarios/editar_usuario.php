<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Usuario</title>
  <link rel="stylesheet" href="../../styles/tailwind.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
<?php include('../../components/header.php');?>
  <main class="container mx-auto mt-8">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Editar Usuario</h2>

    <form id="form-editar-usuario" class="max-w-lg bg-white p-6 rounded-lg shadow-md mx-auto">
      <input type="hidden" id="id_usuario" name="id_usuario">
      <div class="mb-4">
        <label for="nombre" class="block text-gray-700 font-bold mb-2">Nombre</label>
        <input type="text" id="nombre" name="nombre" class="border rounded-md px-4 py-2 w-full">
      </div>
      <div class="mb-4">
        <label for="apellido" class="block text-gray-700 font-bold mb-2">Apellido</label>
        <input type="text" id="apellido" name="apellido" class="border rounded-md px-4 py-2 w-full">
      </div>
      <div class="mb-4">
        <label for="correo_electronico" class="block text-gray-700 font-bold mb-2">Correo Electrónico</label>
        <input type="email" id="correo_electronico" name="correo_electronico" class="border rounded-md px-4 py-2 w-full">
      </div>
      <div class="mb-4">
  <label for="contrasena" class="block text-gray-700 font-bold mb-2">Contraseña</label>
  <input type="password" id="contrasena" name="contrasena" class="border rounded-md px-4 py-2 w-full bg-gray-100" readonly value="********">
</div>
      <div class="mb-4">
        <label for="telefono" class="block text-gray-700 font-bold mb-2">Teléfono</label>
        <input type="tel" id="telefono" name="telefono" class="border rounded-md px-4 py-2 w-full">
      </div>
      <div class="mb-4">
        <label for="direccion" class="block text-gray-700 font-bold mb-2">Dirección</label>
        <input type="text" id="direccion" name="direccion" class="border rounded-md px-4 py-2 w-full">
      </div>
      <div class="mb-4">
        <label for="perfil" class="block text-gray-700 font-bold mb-2">Perfil</label>
        <select id="perfil" name="perfil" class="border rounded-md px-4 py-2 w-full">
          <option value="Administrador">Administrador</option>
          <option value="Usuario">Usuario</option>
        </select>
      </div>
      <div class="mb-4">
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 font-bold">
          Guardar Cambios
        </button>
      </div>
    </form>
  </main>

  <footer class="bg-blue-800 p-4 text-center text-white font-bold">
    <p>Espoch todos los derechos reservados © 2024</p>
  </footer>
  <script src="../../scripts/editar_usuario.js"></script>
</body>
</html>