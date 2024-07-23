<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../usuarios/login.php'); // Redirigir al login si no hay sesión activa
    exit;
}

// Verificar si el usuario es administrador
$isAdmin = isset($_SESSION['user']['Perfil']) && $_SESSION['user']['Perfil'] === 'Administrador';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Listado de Productos</title>
  <link rel="stylesheet" href="../../styles/tailwind.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
  <!-- Contenido Principal -->
  <?php include('../../components/header.php');?>
  <main class="container mx-auto mt-8">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Productos</h2>

    <?php if ($isAdmin): ?>
    <div class="mb-4">
      <button onclick="window.location.href='./add_producto.php'" class="bg-yellow-500 text-white px-4 py-2 rounded-md shadow-sm hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 font-bold">
        Agregar Nuevo Producto
      </button>
    </div>
    <?php endif; ?>

    <!-- Campos de búsqueda avanzada -->
    <div class="mb-4 flex flex-wrap gap-4">
      <div class="w-full md:w-1/4">
        <label for="search-combo" class="block text-gray-700">Buscar por</label>
        <select id="search-combo" class="border rounded-md px-4 py-2 w-full">
          <option value="name-description">Nombre o Descripción</option>
          <option value="price">Precio</option>
          <option value="stock">Stock</option>
          <option value="expiration">Fecha de Caducidad</option>
        </select>
      </div>
      <div class="w-full md:w-1/4">
        <label for="search-input" class="block text-gray-700">Ingrese el valor de búsqueda</label>
        <input id="search-input" type="text" placeholder="Ingrese valor..." class="border rounded-md px-4 py-2 w-full">
        <p id="error-message" class="text-red-500 mt-2 hidden">Mensaje de error</p>
      </div>
    </div>

    <!-- Lista de Productos -->
    <div id="productos-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      <!-- Los productos se cargarán aquí dinámicamente -->
    </div>
  </main>
  <!-- Pie de Página -->
  <footer class="bg-blue-800 p-4 text-center text-white">
    <p>Espoch todos los derechos reservados © 2024</p>
  </footer>
  <script>
    var isAdmin = <?php echo json_encode($isAdmin); ?>;
  </script>
  <script src="../../scripts/list_producto.js"></script>
</body>
</html>
