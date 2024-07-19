<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../usuarios/login.php'); // Redirigir al login si no hay sesión activa
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Categoría</title>
    <link rel="stylesheet" href="../../styles/tailwind.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
<?php include('../../components/header.php');?>
    <main class="container mx-auto mt-8">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">Agregar Nueva Categoría</h2>

        <!-- Formulario para agregar nueva categoría -->
        <form id="form-agregar-categoria" class="bg-white p-6 rounded-lg shadow-md" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="nombre_categoria" class="block text-gray-700 font-bold mb-2">Nombre de la Categoría:</label>
                <input type="text" id="nombre_categoria" name="nombreCategoria" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label for="imagen_categoria" class="block text-gray-700 font-bold mb-2">Imagen de la Categoría:</label>
                <input type="file" id="imagen_categoria" name="imagenCategoria" accept="image/*" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Agregar Categoría
                </button>
            </div>
        </form>
    </main>

    <footer class="bg-blue-800 p-4 text-center text-white">
        <p>Espoch todos los derechos reservados © 2024</p>
    </footer>
    <script src="../../scripts/add_categoria.js"></script>
</body>
</html>
