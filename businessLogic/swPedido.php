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
$isAdmin = isset($_SESSION['user']['Perfil']) && $_SESSION['user']['Perfil'] === 'Administrador';
$objConexion = new ConexionDB();
$objPedido = new Pedido($objConexion);
$objCarrito = new Carrito($objConexion);
$objCarrito->setIdUsuario($idUsuario);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['action'])) {
        switch ($data['action']) {
            case 'realizarPedido':
                $productos = $data['productos'] ?? [];
                $totalAmount = $data['totalAmount'] ?? 0;

                if (empty($productos) || $totalAmount <= 0) {
                    sendError('Datos del pedido no válidos.');
                }

                $objPedido->setIdUsuario($idUsuario);
                $objPedido->setTotal($totalAmount);
                $objPedido->setEstado('Pendiente');

                $idPedido = $objPedido->crearPedido();
                if (!$idPedido) {
                    sendError('Error al crear el pedido.');
                }

                foreach ($productos as $producto) {
                    $idProducto = $producto['idProducto'];
                    $cantidad = $producto['cantidad'];
                    $precio = $producto['precio'];

                    $objPedido->agregarDetallePedido($idProducto, $cantidad, $precio);
                }

                $idCarritoData = $objCarrito->obtenerIdCarritoPorUsuario();
                $idCarrito = $idCarritoData['ID_carrito'] ?? null;

                if ($idCarrito) {
                    $objCarrito->vaciarCarrito($idCarrito);
                }

                sendResponse(array('success' => true, 'message' => 'Pedido realizado con éxito', 'idPedido' => $idPedido));
                break;

            case 'cancelarPedido':
                $idPedido = $data['idPedido'] ?? null;
                if ($idPedido) {
                    $success = $objPedido->cancelarPedido($idPedido);
                    if ($success) {
                        sendResponse(array('success' => true, 'message' => 'Pedido cancelado con éxito'));
                    } else {
                        sendError('Error al cancelar el pedido.');
                    }
                } else {
                    sendError('ID de pedido no válido.');
                }
                break;

            default:
                sendError('Acción no válida.');
                break;
        }
    } else {
        sendError('Acción no especificada.');
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if ($isAdmin) {
        $pedidos = $objPedido->obtenerTodosLosPedidos();
    } else {
        $pedidos = $objPedido->obtenerPedidosPorUsuario($idUsuario);
    }
    sendResponse($pedidos);
} else {
    sendError('Método HTTP no permitido.');
}
?>
