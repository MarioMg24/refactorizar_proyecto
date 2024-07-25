<?php

include '../dataAccess/conexion/Conexion.php';
include '../dataAccess/dataAccessLogic/Pedido.php';
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
$objPedido = new Pedido($objConexion);
$objCarrito = new Carrito($objConexion);
$objCarrito->setIdUsuario($idUsuario);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['action']) && $data['action'] === 'realizarPedido') {
        $idCarritoData = $objCarrito->obtenerIdCarritoPorUsuario();
        $idCarrito = $idCarritoData['ID_carrito'] ?? null;
        
        if (!$idCarrito) {
            sendError('No se encontró un carrito para este usuario.');
        }

        $totalAmount = isset($data['totalAmount']) ? floatval($data['totalAmount']) : 0;

        if ($totalAmount <= 0) {
            sendError('El total del pedido no es válido.');
        }

        $idPedido = $objPedido->crearPedido($idUsuario, $totalAmount);
        if (!$idPedido) {
            sendError('Error al crear el pedido.');
        }

        $productosCarrito = $objCarrito->obtenerProductosEnCarrito();
        foreach ($productosCarrito as $producto) {
            $objPedido->agregarDetallePedido($idPedido, $producto['ID_producto'], $producto['Cantidad'], $producto['Precio']);
        }

        $objCarrito->vaciarCarrito($idCarrito);

        sendResponse(array('success' => true, 'message' => 'Pedido realizado con éxito', 'idPedido' => $idPedido));
    }
}

sendError('Método HTTP no permitido o acción no reconocida.');
?>