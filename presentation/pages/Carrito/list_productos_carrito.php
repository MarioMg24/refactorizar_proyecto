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
    <div class="flex flex-col lg:flex-row">
      <div id="carrito-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 flex-1">
        <!-- Los productos del carrito se cargarán aquí dinámicamente -->
      </div>
      <div id="total-container" class="bg-white shadow-md rounded-lg p-6 ml-0 lg:ml-4 mt-4 lg:mt-0 lg:w-1/4 h-64 overflow-auto">
        <h3 class="text-xl font-bold mb-4 text-gray-800">Total del Carrito</h3>
        <table class="w-full bg-white border rounded-lg shadow-md">
          <thead>
            <tr class="bg-gray-200">
              <th class="py-2 px-4 text-left">Descripción</th>
              <th class="py-2 px-4 text-right">Monto</th>
            </tr>
          </thead>
          <tbody>
            <tr class="border-t">
              <td class="py-2 px-4 text-left">Subtotal</td>
              <td class="py-2 px-4 text-right" id="subtotal-amount">$0.00</td>
            </tr>
            <tr class="border-t bg-gray-100">
              <td class="py-2 px-4 text-left font-bold">Total</td>
              <td class="py-2 px-4 text-right font-bold" id="total-amount">$0.00</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </main>
  <footer class="bg-blue-800 p-4 text-center text-white">
    <p>Espoch todos los derechos reservados © 2024</p>
  </footer>
  <script src="../../scripts/list_productos_carrito.js"></script>
</body>
</html>
