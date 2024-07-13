<?php
include '../dataAccess/conexion/Conexion.php';
include '../dataAccess/dataAccessLogic/Producto.php';

// Read Producto por ID de categoría
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $categoriaId = intval($_GET['categoriaId']); // Obtener el ID de la categoría del parámetro GET
    $objConexion = new ConexionDB();
    $objProducto = new Producto($objConexion);
    $array = $objProducto->getProductosByCategoria($categoriaId); // Obtener productos por categoría
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
    $cantidadDisponible = $_POST['cantidadDisponible'];
    $categoriaId = $_POST['categoriaId'];
    $fechaVencimiento = $_POST['fechaVencimiento'];

    // Procesar la imagen del producto
    $imagenProducto = $_FILES['imagenProducto'];
    $uploadDirectory = '../presentation/pages/productos/img_productos/';
    $uploadedFile = $uploadDirectory . basename($imagenProducto['name']);

    if (move_uploaded_file($imagenProducto['tmp_name'], $uploadedFile)) {
        $rutaImagen = './img_productos/' . basename($imagenProducto['name']);

        // Crear objeto de conexión y objeto de producto
        $objConexion = new ConexionDB();
        $objProducto = new Producto($objConexion);
        $objProducto->setNombreProducto($nombreProducto);
        $objProducto->setDescripcionProducto($descripcionProducto);
        $objProducto->setPrecioProducto($precioProducto);
        $objProducto->setImagenProducto($rutaImagen);
        $objProducto->setCantidadDisponible($cantidadDisponible);
        $objProducto->setCategoriaId($categoriaId);
        $objProducto->setFechaVencimiento($fechaVencimiento);

        // Intentar agregar el producto
        if ($objProducto->addProducto()) {
            $response = array('success' => true, 'message' => 'Producto añadido correctamente');
        } else {
            $response = array('success' => false, 'message' => 'Error al agregar el producto');
        }
    } else {
        $response = array('success' => false, 'message' => 'Error al subir la imagen');
    }

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
