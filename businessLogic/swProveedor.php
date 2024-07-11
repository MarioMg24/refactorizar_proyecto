<?php
include '../dataAccess/conexion/Conexion.php';
include '../dataAccess/dataAccessLogic/Proveedor.php';

// Read Proveedor
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $objConexion = new ConexionDB();
    $objProveedor = new Proveedor($objConexion);
    $array = $objProveedor->readProveedor();
    echo json_encode($array);
    exit;
}

// Delete Proveedor
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $idProveedor = intval($_GET['id']);
    $objConexion = new ConexionDB();
    $objProveedor = new Proveedor($objConexion);
    $objProveedor->setIdProveedor($idProveedor);
    $objProveedor->deleteProveedor();
    $response = array('success' => true, 'message' => 'Proveedor eliminado correctamente');
    echo json_encode($response);
    exit;
}

// Add Proveedor
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombreProveedor = $_POST['nombreProveedor'];
    $contactoProveedor = $_POST['contactoProveedor'];
    $objConexion = new ConexionDB();
    $objProveedor = new Proveedor($objConexion);
    $objProveedor->setNombreProveedor($nombreProveedor);
    $objProveedor->setContactoProveedor($contactoProveedor);
    $objProveedor->addProveedor();
    $response = array('success' => true, 'message' => 'Proveedor añadido correctamente');
    echo json_encode($response);
    exit;
}

// Update Proveedor
else if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $idProveedor = intval($data['idProveedor']);
    $nombreProveedor = $data['nombreProveedor'];
    $contactoProveedor = $data['contactoProveedor'];
    $objConexion = new ConexionDB();
    $objProveedor = new Proveedor($objConexion);
    $objProveedor->setIdProveedor($idProveedor);
    $objProveedor->setNombreProveedor($nombreProveedor);
    $objProveedor->setContactoProveedor($contactoProveedor);
    $objProveedor->updateProveedor();
    $response = array('success' => true, 'message' => 'Proveedor actualizado correctamente');
    echo json_encode($response);
    exit;
}
?>
