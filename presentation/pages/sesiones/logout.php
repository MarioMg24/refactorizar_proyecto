<?php
session_start();
session_unset(); // Eliminar todas las variables de sesión
session_destroy(); // Destruir la sesión

header('Location: ../usuarios/login.php'); // Redirigir al login
exit;
?>