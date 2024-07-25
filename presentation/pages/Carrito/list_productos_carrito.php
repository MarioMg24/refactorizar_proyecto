<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../usuarios/login.php'); // Redirigir al login si no hay sesión activa
    exit;
}

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carrito de Compras</title>
  <link rel="stylesheet" href="../../styles/tailwind.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
  <?php include('../../components/header.php');?>
  <main class="container mx-auto mt-8">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Carrito de Compras</h2>
    <div id="carrito-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      <!-- Los productos del carrito se cargarán aquí dinámicamente -->
    </div>
    <div class="mt-4">
      <button id="checkout-button" class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
        Proceder al Pago
      </button>
    </div>
  </main>
  <footer class="bg-blue-800 p-4 text-center text-white">
    <p>Espoch todos los derechos reservados © 2024</p>
  </footer>
  <script src="../../scripts/list_productos_carrito.js"></script>
</body>
</html>
