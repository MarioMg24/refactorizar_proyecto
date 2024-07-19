<?php
if (!isset($_SESSION['user'])) {
    header('Location: ../usuarios/login.php');
    exit;
}
$user = $_SESSION['user'];
?>
<header class="bg-blue-800 p-4 shadow-md">
  <div class="container mx-auto flex justify-between items-center">
    <h1 class="text-white text-2xl">App de Inventario</h1>
    <nav>
      <ul class="flex space-x-4">
        <li><a href="../categorias/list_categoria.php" class="text-white hover:text-gray-300">Categorías</a></li>
        <li><a href="../productos/list_producto.php" class="text-white hover:text-gray-300">Productos</a></li>
        <li><a href="../proveedores/list_proveedor.php" class="text-white hover:text-gray-300">Proveedores</a></li>
        <li><a href="../usuarios/list_usuario.php" class="text-white hover:text-gray-300">Usuarios</a></li>
        <li>
          <a href="../usuarios/perfil_usuario.php" class="text-white hover:text-gray-300">
            <?php echo htmlspecialchars($user['Nombre'] . ' ' . $user['Apellido']); ?>
          </a>
        </li>
        <li><a href="../sesiones/logout.php" class="text-white hover:text-gray-300">Cerrar sesión</a></li>
      </ul>
    </nav>
  </div>
</header>