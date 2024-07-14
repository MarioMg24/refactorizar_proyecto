<?php
include '../dataAccess/conexion/Conexion.php';
include '../dataAccess/dataAccessLogic/Proveedor.php';

// Read Proveedor by ID
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id_proveedor'])) {
    $idProveedor = intval($_GET['id_proveedor']);
    $objConexion = new ConexionDB();
    $objProveedor = new Proveedor($objConexion);
    $array = $objProveedor->readProveedorById($idProveedor);
    echo json_encode($array);
    exit;
}

// Read Proveedor
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['listarProveedoresConProductos']) && $_GET['listarProveedoresConProductos'] == 'true') {
        // Obtener proveedores con productos asociados
        $objConexion = new ConexionDB();
        $objProveedor = new Proveedor($objConexion);
        $array = $objProveedor->readProveedoresConProductos();
        echo json_encode($array);
        exit;
    } else {
        $objConexion = new ConexionDB();
        $objProveedor = new Proveedor($objConexion);
        $array = $objProveedor->readProveedor();
        echo json_encode($array);
        exit;
    }
}

// Delete Proveedor
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $idProveedor = intval($_GET['id']);
    $objConexion = new ConexionDB();
    $objProveedor = new Proveedor($objConexion);
    $objProveedor->setIdProveedor($idProveedor);
    
    // Primero eliminamos las relaciones en ProveedorProducto
    if ($objProveedor->deleteProveedorProductoRelations()) {
        // Luego eliminamos el proveedor
        if ($objProveedor->deleteProveedor()) {
            $response = array('success' => true, 'message' => 'Proveedor y sus relaciones eliminados correctamente');
        } else {
            $response = array('success' => false, 'message' => 'Error al eliminar el proveedor');
        }
    } else {
        $response = array('success' => false, 'message' => 'Error al eliminar las relaciones del proveedor');
    }
    echo json_encode($response);
    exit;
}

// Add Proveedor
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $nombreProveedor = $data['nombreProveedor'];
    $contactoProveedor = $data['contactoProveedor'];
    $terminosNegociacion = $data['terminosNegociacion'];
    $productosSeleccionados = $data['productosSeleccionados'];

    $objConexion = new ConexionDB();
    $objProveedor = new Proveedor($objConexion);
    $objProveedor->setNombreProveedor($nombreProveedor);
    $objProveedor->setContactoProveedor($contactoProveedor);
    $objProveedor->setTerminosNegociacion($terminosNegociacion);
    
    $idProveedor = $objProveedor->addProveedor();
    
    if ($idProveedor) {
        // Añadir productos al proveedor
        $allProductsAdded = true;
        foreach ($productosSeleccionados as $idProducto) {
            if (!$objProveedor->addProveedorProducto($idProveedor, $idProducto)) {
                $allProductsAdded = false;
                break;
            }
        }
        
        if ($allProductsAdded) {
            $response = array('success' => true, 'message' => 'Proveedor y productos añadidos correctamente');
        } else {
            $response = array('success' => false, 'message' => 'Proveedor añadido, pero hubo un error al añadir algunos productos');
        }
    } else {
        $response = array('success' => false, 'message' => 'Error al añadir el proveedor');
    }
    
    echo json_encode($response);
    exit;
}

// Update Proveedor
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $idProveedor = intval($data['idProveedor']);
    $nombreProveedor = $data['nombreProveedor'];
    $contactoProveedor = $data['contactoProveedor'];
    $terminosNegociacion = $data['terminosNegociacion'];
    $productosSeleccionados = $data['productosSeleccionados'];

    $objConexion = new ConexionDB();
    $objProveedor = new Proveedor($objConexion);
    $objProveedor->setIdProveedor($idProveedor);
    $objProveedor->setNombreProveedor($nombreProveedor);
    $objProveedor->setContactoProveedor($contactoProveedor);
    $objProveedor->setTerminosNegociacion($terminosNegociacion);
    
    // Obtener los datos actuales del proveedor
    $currentData = $objProveedor->readProveedorById($idProveedor);

    // Verificar si los datos proporcionados son idénticos a los datos existentes
    $datosIguales = (
        $currentData['Nombre_proveedor'] === $nombreProveedor &&
        $currentData['Contacto'] === $contactoProveedor &&
        $currentData['Terminos_negociacion'] === $terminosNegociacion
    );

    if ($datosIguales) {
        // Actualizar solo los productos del proveedor
        $objProveedor->deleteAllProveedorProducto($idProveedor);
        $allProductsAdded = true;
        foreach ($productosSeleccionados as $idProducto) {
            if (!$objProveedor->addProveedorProducto($idProveedor, $idProducto)) {
                $allProductsAdded = false;
                break;
            }
        }
        
        if ($allProductsAdded) {
            $response = array('success' => true, 'message' => 'Productos del proveedor actualizados correctamente');
        } else {
            $response = array('success' => false, 'message' => 'Hubo un error al actualizar algunos productos');
        }
    } else {
        if ($objProveedor->updateProveedor()) {
            // Actualizar productos del proveedor
            $objProveedor->deleteAllProveedorProducto($idProveedor);
            $allProductsAdded = true;
            foreach ($productosSeleccionados as $idProducto) {
                if (!$objProveedor->addProveedorProducto($idProveedor, $idProducto)) {
                    $allProductsAdded = false;
                    break;
                }
            }
            
            if ($allProductsAdded) {
                $response = array('success' => true, 'message' => 'Proveedor y productos actualizados correctamente');
            } else {
                $response = array('success' => false, 'message' => 'Proveedor actualizado, pero hubo un error al actualizar algunos productos');
            }
        } else {
            $response = array('success' => false, 'message' => 'Error al actualizar el proveedor');
        }
    }

    echo json_encode($response);
    exit;
}
?>
