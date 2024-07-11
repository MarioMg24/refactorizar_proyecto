<?php
include '../dataAccess/conexion/Conexion.php';
include '../dataAccess/dataAccessLogic/Producto.php';

// Read Producto
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $objConexion = new ConexionDB();
    $objProducto = new Producto($objConexion);
    $array = $objProducto->readProducto();
    echo json_encode($array);
    exit;
}

// Delete Producto
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $idProducto = intval($_GET['id']);
    $objConexion = new ConexionDB();
    $objProducto = new Producto($objConexion);
    $objProducto->setIdProducto($idProducto);
    $objProducto->deleteProducto();
    $response = array('success' => true, 'message' => 'Producto eliminado correctamente');
    echo json_encode($response);
    exit;
}

// Add Producto
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombreProducto = $_POST['nombreProducto'];
    $descripcionProducto = $_POST['descripcionProducto'];
    $precioProducto = $_POST['precioProducto'];
    $imagenProducto = $_POST['imagenProducto'];
    $cantidadDisponible = $_POST['cantidadDisponible'];
    $categoriaId = $_POST['categoriaId'];
    $fechaVencimiento = $_POST['fechaVencimiento'];
    $objConexion = new ConexionDB();
    $objProducto = new Producto($objConexion);
    $objProducto->setNombreProducto($nombreProducto);
    $objProducto->setDescripcionProducto($descripcionProducto);
    $objProducto->setPrecioProducto($precioProducto);
    $objProducto->setImagenProducto($imagenProducto);
    $objProducto->setCantidadDisponible($cantidadDisponible);
    $objProducto->setCategoriaId($categoriaId);
    $objProducto->setFechaVencimiento($fechaVencimiento);
    $objProducto->addProducto();
    $response = array('success' => true, 'message' => 'Producto añadido correctamente');
    echo json_encode($response);
    exit;
}

// Update Producto
else if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $idProducto = intval($data['idProducto']);
    $nombreProducto = $data['nombreProducto'];
    $descripcionProducto = $data['descripcionProducto'];
    $precioProducto = $data['precioProducto'];
    $imagenProducto = $data['imagenProducto'];
    $cantidadDisponible = $data['cantidadDisponible'];
    $categoriaId = $data['categoriaId'];
    $fechaVencimiento = $data['fechaVencimiento'];
    $objConexion = new ConexionDB();
    $objProducto = new Producto($objConexion);
    $objProducto->setIdProducto($idProducto);
    $objProducto->setNombreProducto($nombreProducto);
    $objProducto->setDescripcionProducto($descripcionProducto);
    $objProducto->setPrecioProducto($precioProducto);
    $objProducto->setImagenProducto($imagenProducto);
    $objProducto->setCantidadDisponible($cantidadDisponible);
    $objProducto->setCategoriaId($categoriaId);
    $objProducto->setFechaVencimiento($fechaVencimiento);
    $objProducto->updateProducto();
    $response = array('success' => true, 'message' => 'Producto actualizado correctamente');
    echo json_encode($response);
    exit;
}
?>
