<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Categoría</title>
  <link rel="stylesheet" href="../../styles/tailwind.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
<?php include('../../components/header.php');?>
  <!-- Contenido Principal -->
  <main class="container mx-auto mt-8">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Editar Categoría</h2>

    <!-- Formulario para editar categoría -->
    <form id="editar-categoria-form" class="bg-white p-6 rounded-lg shadow-md" enctype="multipart/form-data">
      <input type="hidden" id="ID_categoria" name="ID_categoria">
      <div class="mb-4">
        <label for="Nombre_categoria" class="block text-gray-700">Nombre de la Categoría</label>
        <input type="text" id="Nombre_categoria" name="Nombre_categoria" class="w-full p-2 border border-gray-300 rounded-md" required>
      </div>
      <div class="mb-4">
        <label for="Imagen_categoria" class="block text-gray-700">Imagen de la Categoría</label>
        <input type="file" id="Imagen_categoria" name="Imagen_categoria" accept="image/*" class="w-full p-2 border border-gray-300 rounded-md">
      </div>
      <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Guardar Cambios</button>
    </form>
    
  </main>

  <!-- Pie de Página -->
  <footer class="bg-blue-800 p-4 text-center text-white font-bold">
    <p>Espoch todos los derechos reservados © 2024</p>
  </footer>

  <script src="../../scripts/editar_categoria.js"></script>
</body>
</html>
