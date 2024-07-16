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
// Update Producto
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Obtener el cuerpo de la solicitud PUT
    $putData = file_get_contents("php://input");
    $boundary = substr($putData, 0, strpos($putData, "\r\n"));

    // Parsear los datos del formulario
    $parts = array_slice(explode($boundary, $putData), 1);
    $data = array();

    foreach ($parts as $part) {
        if ($part == "--\r\n") break;

        $part = ltrim($part, "\r\n");
        list($raw_headers, $body) = explode("\r\n\r\n", $part, 2);

        $raw_headers = explode("\r\n", $raw_headers);
        $headers = array();
        foreach ($raw_headers as $header) {
            list($name, $value) = explode(':', $header);
            $headers[strtolower($name)] = ltrim($value, ' ');
        }

        if (isset($headers['content-disposition'])) {
            $filename = null;
            preg_match(
                '/^(.+); *name="([^"]+)"(; *filename="([^"]+)")?/',
                $headers['content-disposition'],
                $matches
            );
            $name = $matches[2];
            $filename = isset($matches[4]) ? $matches[4] : null;

            if ($filename !== null) {
                $data[$name] = array(
                    'filename' => $filename,
                    'content' => $body
                );
            } else {
                $data[$name] = substr($body, 0, strlen($body) - 2);
            }
        }
    }

    // Procesar los datos
    $idProducto = isset($data['id_producto']) ? intval($data['id_producto']) : null;
    $nombreProducto = isset($data['nombre']) ? $data['nombre'] : '';
    $descripcionProducto = isset($data['descripcion']) ? $data['descripcion'] : '';
    $precioProducto = isset($data['precio']) ? floatval($data['precio']) : 0;
    $cantidadDisponible = isset($data['cantidad']) ? intval($data['cantidad']) : 0;
    $categoriaId = isset($data['categoria']) ? intval($data['categoria']) : null;
    $fechaVencimiento = isset($data['fecha_caducidad']) ? $data['fecha_caducidad'] : null;
    $imagenProducto = isset($data['imagen']) ? $data['imagen'] : null;

    if ($idProducto !== null) {
        $objConexion = new ConexionDB();
        $objProducto = new Producto($objConexion);
        
        // Obtener el producto existente
        $productoExistente = $objProducto->getProductoByIdAndCategoria($idProducto, $categoriaId);
        
        if ($productoExistente) {
            $objProducto->setIdProducto($idProducto);
            $objProducto->setNombreProducto($nombreProducto);
            $objProducto->setDescripcionProducto($descripcionProducto);
            $objProducto->setPrecioProducto($precioProducto);
            $objProducto->setCantidadDisponible($cantidadDisponible);
            $objProducto->setCategoriaId($categoriaId);
            $objProducto->setFechaVencimiento($fechaVencimiento);

            // Manejar la imagen
            if ($imagenProducto !== null) {
                $uploadDirectory = '../presentation/pages/productos/img_productos/';
                $uploadedFile = $uploadDirectory . $imagenProducto['filename'];

                if (file_put_contents($uploadedFile, $imagenProducto['content'])) {
                    $rutaImagen = './img_productos/' . $imagenProducto['filename'];
                    $objProducto->setImagenProducto($rutaImagen);
                } else {
                    $response = array('success' => false, 'message' => 'Error al subir la nueva imagen');
                    echo json_encode($response);
                    exit;
                }
            } else {
                // Mantener la imagen existente
                $objProducto->setImagenProducto($productoExistente['Imagen_producto']);
            }

            if ($objProducto->updateProducto()) {
                $response = array('success' => true, 'message' => 'Producto actualizado correctamente');
            } else {
                $response = array('success' => false, 'message' => 'Error al actualizar el producto');
            }
        } else {
            $response = array('success' => false, 'message' => 'Producto no encontrado');
        }
    } else {
        $response = array('success' => false, 'message' => 'No se proporcionó ID de producto');
    }

    echo json_encode($response);
    exit;
}