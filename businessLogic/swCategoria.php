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
else if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $idCategoria = intval($data['idCategoria']);
    $nombreCategoria = $data['nombreCategoria'];
    $imagenCategoria = $data['imagenCategoria'];
    $objConexion = new ConexionDB();
    $objCategoria = new Categoria($objConexion);
    $objCategoria->setIdCategoria($idCategoria);
    $objCategoria->setNombreCategoria($nombreCategoria);
    $objCategoria->setImagenCategoria($imagenCategoria);
    $objCategoria->updateCategoria();
    $response = array('success' => true, 'message' => 'Categoria actualizada correctamente');
    echo json_encode($response);
    exit;
}
?>
