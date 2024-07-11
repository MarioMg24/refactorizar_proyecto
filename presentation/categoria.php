<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Categoría</title>
    <link rel="stylesheet" href="../presentation/styles/tailwind.css">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <h1>Gestión de Categorías</h1>
    
    <!-- Formulario para añadir una categoría -->
    <h2>Añadir Categoría</h2>
    <form id="addCategoriaForm" method="post" action="./dataAccessLogic/User.php">
        <label for="nombreCategoria">Nombre de la Categoría:</label>
        <input type="text" id="nombreCategoria" name="nombreCategoria" required><br><br>
        
        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required></textarea><br><br>
        
        <button type="submit">Añadir Categoría</button>
    </form>
</body>
</html>
