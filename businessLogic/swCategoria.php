<?php
include '../dataAccess/conexion/Conexion.php';
include '../dataAccess/dataAccessLogic/Categoria.php';
include '../dataAccess/dataAccessLogic/Producto.php';

// Read Categoria(s)
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $objConexion = new ConexionDB();
    $objCategoria = new Categoria($objConexion);

    if (isset($_GET['id'])) {
        $idCategoria = intval($_GET['id']);
        $categoria = $objCategoria->readCategoriaById($idCategoria);
        echo json_encode($categoria);
        exit;
    } else {
        $array = $objCategoria->readCategoria();
        echo json_encode($array);
        exit;
    }
}

// Delete Categoria
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $idCategoria = intval($_GET['id']);
    $objConexion = new ConexionDB();
    $objCategoria = new Categoria($objConexion);
    $objProducto = new Producto($objConexion);

    $objCategoria->setIdCategoria($idCategoria);
    
    // Obtener todos los productos asociados a la categoría
    $productosAsociados = $objProducto->getProductosByCategoria($idCategoria);

    foreach ($productosAsociados as $producto) {
        $objProducto->setIdProducto($producto['ID_producto']);
        // Eliminar registros en DetallePedido
        $objProducto->deleteDetallePedidoByProducto($producto['ID_producto']);
        // Eliminar registros en ProveedorProducto
        $objProducto->deleteProveedorProductoByProducto($producto['ID_producto']);
        // Eliminar el producto
        $objProducto->deleteProducto();
    }

    // Eliminar la categoría
    if ($objCategoria->deleteCategoria()) {
        $response = array('success' => true, 'message' => 'Categoria y productos asociados eliminados correctamente');
    } else {
        $response = array('success' => false, 'message' => 'Error al eliminar la categoría');
    }

    echo json_encode($response);
    exit;
}

// Add Categoria
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombreCategoria = $_POST['nombreCategoria'];
    $imagenCategoria = $_FILES['imagenCategoria']; // Obtener la imagen desde el formulario

    // Ruta donde se guardará la imagen
    $uploadDirectory = '../presentation/pages/categorias/img_categorias/';
    $uploadedFile = $uploadDirectory . basename($imagenCategoria['name']);

    if (move_uploaded_file($imagenCategoria['tmp_name'], $uploadedFile)) {
        // Guardar la ruta de la imagen en la base de datos
        $rutaImagen = './img_categorias/' . basename($imagenCategoria['name']);

        // Crear objeto de conexión y objeto de categoría
        $objConexion = new ConexionDB();
        $objCategoria = new Categoria($objConexion);
        $objCategoria->setNombreCategoria($nombreCategoria);
        $objCategoria->setImagenCategoria($rutaImagen);

        // Intentar agregar la categoría
        if ($objCategoria->addCategoria()) {
            $response = array('success' => true, 'message' => 'Categoría añadida correctamente');
        } else {
            $response = array('success' => false, 'message' => 'Error al agregar la categoría');
        }
    } else {
        $response = array('success' => false, 'message' => 'Error al subir la imagen');
    }

    echo json_encode($response);
    exit;
}

// Update Categoria
// Update Categoria
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
    $idCategoria = isset($data['idCategoria']) ? intval($data['idCategoria']) : null;
    $nombreCategoria = isset($data['nombreCategoria']) ? $data['nombreCategoria'] : '';
    $imagenCategoria = isset($data['imagenCategoria']) ? $data['imagenCategoria'] : null;

    if ($idCategoria !== null) {
        $objConexion = new ConexionDB();
        $objCategoria = new Categoria($objConexion);
        
        // Obtener la categoría existente
        $categoriaExistente = $objCategoria->readCategoriaById($idCategoria);
        
        if ($categoriaExistente) {
            $objCategoria->setIdCategoria($idCategoria);

            // Actualizar el nombre si se proporciona uno nuevo
            if (!empty($nombreCategoria)) {
                $objCategoria->setNombreCategoria($nombreCategoria);
            } else {
                $objCategoria->setNombreCategoria($categoriaExistente['Nombre_categoria']);
            }

            // Manejar la imagen
            if ($imagenCategoria !== null) {
                $uploadDirectory = '../presentation/pages/categorias/img_categorias/';
                $uploadedFile = $uploadDirectory . $imagenCategoria['filename'];

                if (file_put_contents($uploadedFile, $imagenCategoria['content'])) {
                    $rutaImagen = './img_categorias/' . $imagenCategoria['filename'];
                    $objCategoria->setImagenCategoria($rutaImagen);
                } else {
                    $response = array('success' => false, 'message' => 'Error al subir la nueva imagen');
                    echo json_encode($response);
                    exit;
                }
            } else {
                // Mantener la imagen existente
                $objCategoria->setImagenCategoria($categoriaExistente['Imagen_categoria']);
            }

            if ($objCategoria->updateCategoria()) {
                $response = array('success' => true, 'message' => 'Categoría actualizada correctamente');
            } else {
                $response = array('success' => false, 'message' => 'Error al actualizar la categoría');
            }
        } else {
            $response = array('success' => false, 'message' => 'Categoría no encontrada');
        }
    } else {
        $response = array('success' => false, 'message' => 'No se proporcionó ID de categoría');
    }

    echo json_encode($response);
    exit;
}
?>
