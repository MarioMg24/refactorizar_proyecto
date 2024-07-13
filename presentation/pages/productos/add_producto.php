<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro de Productos</title>
  <link rel="stylesheet" href="../../styles/tailwind.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
<?php include('../../components/header.php');?>
<main class="container mx-auto mt-8">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Registro de Productos</h2>

    <!-- Formulario de Registro de Productos -->
    <form id="registro-producto-form" class="max-w-lg mx-auto bg-white p-6 rounded-md shadow-md" enctype="multipart/form-data">
        <!-- Campos del formulario (nombre, descripción, precio, imagen, cantidad, categoría, fecha de caducidad opcional) -->
        <div class="mb-4">
            <label for="nombre-producto" class="block text-sm font-bold text-gray-700">Nombre del Producto</label>
            <input type="text" id="nombre-producto" name="nombre-producto" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
        </div>

        <div class="mb-4">
            <label for="descripcion-producto" class="block text-sm font-bold text-gray-700">Descripción</label>
            <textarea id="descripcion-producto" name="descripcion-producto" rows="3" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
        </div>

        <div class="mb-4">
            <label for="precio-producto" class="block text-sm font-bold text-gray-700">Precio</label>
            <input type="number" step="0.01" id="precio-producto" name="precio-producto" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
        </div>

        <div class="mb-4">
            <label for="imagen-producto" class="block text-sm font-bold text-gray-700">Imagen del Producto</label>
            <input type="file" id="imagen-producto" name="imagen-producto" accept="image/*" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
        </div>

        <div class="mb-4">
            <label for="cantidad-producto" class="block text-sm font-bold text-gray-700">Cantidad Disponible</label>
            <input type="number" id="cantidad-producto" name="cantidad-producto" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
        </div>

        <div class="mb-4">
            <label for="categoria-producto" class="block text-sm font-bold text-gray-700">Categoría</label>
            <select id="categoria-producto" name="categoria-producto" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                <!-- cargar los nombres de categorias dinamicamente -->
            </select>
        </div>

        <div class="mb-4">
            <label for="fecha-caducidad" class="block text-sm font-bold text-gray-700">Fecha de Caducidad (opcional)</label>
            <input type="date" id="fecha-caducidad" name="fecha-caducidad" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
        </div>

        <div class="mb-4">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 font-bold">Registrar Producto</button>
        </div>
    </form>

</main>

<footer class="bg-blue-800 p-4 text-center text-white font-bold">
    <p>Espoch todos los derechos reservados © 2024</p>
</footer>
<script src="../../scripts/add_producto.js"></script>
</body>
</html>
