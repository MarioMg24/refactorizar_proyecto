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
    $categoriaId = isset($data['categoria']) ? intval($data['categoria']) : null;

    if ($idProducto !== null && $categoriaId !== null) {
        $objConexion = new ConexionDB();
        $objProducto = new Producto($objConexion);
        
        // Obtener el producto existente
        $productoExistente = $objProducto->getProductoByIdAndCategoria($idProducto, $categoriaId);
        
        if ($productoExistente) {
            $objProducto->setIdProducto($idProducto);
            
            // Actualizar solo los campos que han cambiado
            $objProducto->setNombreProducto(isset($data['nombre']) ? $data['nombre'] : $productoExistente['Nombre_producto']);
            $objProducto->setDescripcionProducto(isset($data['descripcion']) ? $data['descripcion'] : $productoExistente['Descripcion']);
            $objProducto->setPrecioProducto(isset($data['precio']) ? floatval($data['precio']) : $productoExistente['Precio']);
            $objProducto->setCantidadDisponible(isset($data['cantidad']) ? intval($data['cantidad']) : $productoExistente['Cantidad_disponible']);
            $objProducto->setCategoriaId($categoriaId);
            $objProducto->setFechaVencimiento(isset($data['fecha_caducidad']) ? $data['fecha_caducidad'] : $productoExistente['Fecha_caducidad']);

            // Manejar la imagen
            if (isset($data['imagen']) && $data['imagen']['filename'] !== '') {
                $uploadDirectory = '../presentation/pages/productos/img_productos/';
                $uploadedFile = $uploadDirectory . $data['imagen']['filename'];

                if (file_put_contents($uploadedFile, $data['imagen']['content'])) {
                    $rutaImagen = './img_productos/' . $data['imagen']['filename'];
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
        $response = array('success' => false, 'message' => 'No se proporcionó ID de producto o categoría');
    }

    echo json_encode($response);
    exit;
}