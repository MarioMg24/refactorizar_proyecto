<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perfil de Usuario</title>
  <link rel="stylesheet" href="../../styles/tailwind.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
  <?php include('../../components/header.php');?>
  
  <main class="container mx-auto mt-8">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Perfil de Usuario</h2>
    
    <div id="perfil-container" class="bg-white shadow-md rounded-lg p-6">
      <!-- Los datos del perfil se cargarán aquí dinámicamente -->
    </div>
  </main>

  <footer class="bg-blue-800 p-4 text-center text-white font-bold mt-8">
    <p>Espoch todos los derechos reservados © 2024</p>
  </footer>
  
  <script src="../../scripts/perfil_usuario.js"></script>
</body>
</html>