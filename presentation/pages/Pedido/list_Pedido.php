<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../usuarios/login.php'); // Redirigir al login si no hay sesión activa
    exit;
}

$user = $_SESSION['user'];
$isAdmin = isset($user['Perfil']) && $user['Perfil'] === 'Administrador';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Pedidos</title>
    <link rel="stylesheet" href="../../styles/tailwind.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <?php include('../../components/header.php');?>
    <main class="container mx-auto mt-8">
        <h2 class="text-2xl font-bold mb-4 text-gray-800"><?= $isAdmin ? 'Todos los Pedidos' : 'Mis Pedidos' ?></h2>
        <div id="pedidos-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Los pedidos se cargarán aquí dinámicamente -->
        </div>
    </main>
    <footer class="bg-blue-800 p-4 text-center text-white">
        <p>Espoch todos los derechos reservados © 2024</p>
    </footer>
    <script>
        var isAdmin = <?php echo json_encode($isAdmin); ?>;
    </script>
    <script src="../../scripts/list_Pedido.js"></script>
</body>
</html>
