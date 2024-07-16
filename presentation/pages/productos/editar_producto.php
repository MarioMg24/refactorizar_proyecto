<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="../../styles/tailwind.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <?php include('../../components/header.php');?>
    <main class="container mx-auto mt-8">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">Editar Producto</h2>

        <form id="form-editar-producto" class="bg-white p-6 rounded-lg shadow-md">
            <input type="hidden" id="id_producto" name="id_producto">

            <div class="mb-4">
                <label for="nombre" class="block text-gray-700 text-sm font-bold mb-2">Nombre del Producto:</label>
                <input type="text" id="nombre" name="nombre" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
                <label for="descripcion" class="block text-gray-700 text-sm font-bold mb-2">Descripción:</label>
                <textarea id="descripcion" name="descripcion" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
            </div>

            <div class="mb-4">
                <label for="precio" class="block text-gray-700 text-sm font-bold mb-2">Precio:</label>
                <input type="number" id="precio" name="precio" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" step="0.01">
            </div>

            <div class="mb-4">
                <label for="cantidad" class="block text-gray-700 text-sm font-bold mb-2">Cantidad Disponible:</label>
                <input type="number" id="cantidad" name="cantidad" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
                <label for="imagen" class="block text-gray-700 text-sm font-bold mb-2">Imagen:</label>
                <input type="file" id="imagen" name="imagen" accept="image/*" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
                <label for="fecha_caducidad" class="block text-gray-700 text-sm font-bold mb-2">Fecha de Caducidad:</label>
                <input type="date" id="fecha_caducidad" name="fecha_caducidad" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
                <label for="categoria" class="block text-gray-700 text-sm font-bold mb-2">Categoría:</label>
                <select id="categoria" name="categoria" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <!-- Las opciones se cargarán dinámicamente -->
                </select>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </main>

    <footer class="bg-blue-800 p-4 text-center text-white">
        <p>Espoch todos los derechos reservados © 2024</p>
    </footer>
    <script src="../../scripts/editar_producto.js"></script>
</body>
</html>