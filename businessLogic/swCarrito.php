<?php

include '../dataAccess/conexion/Conexion.php';
include '../dataAccess/dataAccessLogic/Carrito.php';

function sendResponse($data) {
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function sendError($message) {
    header('Content-Type: application/json');
    echo json_encode(array('success' => false, 'message' => $message));
    exit;
}

session_start();
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['ID_usuario'])) {
    sendError('Usuario no autenticado o sesión inválida');
}

$idUsuario = $_SESSION['user']['ID_usuario'];
$objConexion = new ConexionDB();
$objCarrito = new Carrito($objConexion);
$objCarrito->setIdUsuario($idUsuario);

// Obtener o crear carrito para el usuario
$idCarritoData = $objCarrito->obtenerIdCarritoPorUsuario();
if (empty($idCarritoData)) {
    $objCarrito->crearCarritoParaUsuario();
    $idCarritoData = $objCarrito->obtenerIdCarritoPorUsuario();
}
$idCarrito = $idCarritoData['ID_carrito'] ?? null;
$objCarrito->setIdCarrito($idCarrito);

// Manejar solicitudes POST para agregar productos
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['idProducto']) && isset($data['cantidad'])) {
        $success = $objCarrito->agregarProductoAlCarrito($data['idProducto'], $data['cantidad']);
        sendResponse(array('success' => $success, 'message' => $success ? 'Producto añadido al carrito' : 'Error al añadir producto'));
    } elseif (isset($data['action']) && $data['action'] === 'actualizarCantidad') {
        $idProducto = $data['idProducto'];
        $nuevaCantidad = $data['nuevaCantidad'];
        $success = $objCarrito->actualizarCantidadProducto($idProducto, $nuevaCantidad);
        sendResponse(array('success' => $success, 'message' => $success ? 'Cantidad actualizada' : 'Error al actualizar cantidad'));
    }
    sendError('Datos incompletos.');
}

// Manejar solicitudes GET para obtener productos en el carrito
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $productos = $objCarrito->obtenerProductosEnCarrito();
    sendResponse($productos);
}

// Manejar solicitudes DELETE para quitar productos
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $idProducto = isset($_GET['idProducto']) ? intval($_GET['idProducto']) : 0;
    if ($idProducto <= 0) {
        sendError('ID de producto no válido.');
    }

    $success = $objCarrito->quitarProductoDelCarrito($idProducto);
    sendResponse(array('success' => $success, 'message' => $success ? 'Producto eliminado del carrito' : 'Error al eliminar producto'));
}

sendError('Método HTTP no permitido.');
?>
