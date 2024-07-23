<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../usuarios/login.php');
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
  <title>Listado de Usuarios</title>
  <link rel="stylesheet" href="../../styles/tailwind.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
  <!-- Contenido Principal -->
  <?php include('../../components/header.php');?>
  <main class="container mx-auto mt-8">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Usuarios</h2>

    <!-- Tabla de Usuarios -->
    <div class="overflow-x-auto">
      <table class="min-w-full bg-white border-gray-300 shadow-md rounded-md overflow-hidden">
        <thead class="bg-gray-200">
          <tr>
            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 font-medium text-gray-700 uppercase tracking-wider">ID</th>
            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 font-medium text-gray-700 uppercase tracking-wider">Nombre</th>
            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 font-medium text-gray-700 uppercase tracking-wider">Correo Electrónico</th>
            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 font-medium text-gray-700 uppercase tracking-wider">Perfil</th>
            <th class="px-6 py-3 border-b-2 border-gray-300"></th>
          </tr>
        </thead>
        <tbody id="usuarios-body">
          <!-- Filas de usuarios se cargarán aquí dinámicamente -->
        </tbody>
      </table>
    </div>
  </main>
  <!-- Pie de Página -->
  <footer class="bg-blue-800 p-4 text-center text-white">
    <p>Espoch todos los derechos reservados © 2024</p>
  </footer>
  <script>
    var isAdmin = <?php echo json_encode($isAdmin); ?>;
  </script>
  <script src="../../scripts/list_usuario.js"></script>
</body>
</html>
