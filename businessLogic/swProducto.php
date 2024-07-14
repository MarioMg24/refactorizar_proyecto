<?php
include '../dataAccess/conexion/Conexion.php';
include '../dataAccess/dataAccessLogic/Producto.php';

// Read Producto por ID de categoría
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['getAllProducts'])) {
        $objConexion = new ConexionDB();
        $objProducto = new Producto($objConexion);
        $productos = $objProducto->getAllProducts();
        echo json_encode($productos);
        exit;
    } elseif (isset($_GET['categoriaId']) && isset($_GET['id_producto'])) {
        $categoriaId = intval($_GET['categoriaId']);
        $idProducto = intval($_GET['id_producto']);

        $objConexion = new ConexionDB();
        $objProducto = new Producto($objConexion);
        $producto = $objProducto->getProductoByIdAndCategoria($idProducto, $categoriaId);

        echo json_encode($producto);
        exit;
    } elseif (isset($_GET['categoriaId'])) {
        $categoriaId = intval($_GET['categoriaId']);

        $objConexion = new ConexionDB();
        $objProducto = new Producto($objConexion);
        $array = $objProducto->getProductosByCategoria($categoriaId);

        echo json_encode($array);
        exit;
    }
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
    $cantidadDisponible = $data['cantidadDisponible'];
    $categoriaId = $data['categoriaId'];
    $fechaVencimiento = $data['fechaVencimiento'];
    $imagenProducto = $data['imagenProducto']; // Asumiendo que la imagen se envía como una URL o ruta

    // Crear objeto de conexión y objeto de producto
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

    if ($objProducto->updateProducto()) {
        $response = array('success' => true, 'message' => 'Producto actualizado correctamente');
    } else {
        $response = array('success' => false, 'message' => 'Error al actualizar el producto');
    }

    echo json_encode($response);
    exit;
}