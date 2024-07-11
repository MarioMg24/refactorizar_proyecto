<?php
include '../dataAccess/conexion/Conexion.php';
include '../dataAccess/dataAccessLogic/Usuario.php';

// Read Usuario
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $objConexion = new ConexionDB();
    $objUsuario = new Usuario($objConexion);
    $array = $objUsuario->readUsuario();
    echo json_encode($array);
    exit;
}

// Delete Usuario
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $idUsuario = intval($_GET['id']);
    $objConexion = new ConexionDB();
    $objUsuario = new Usuario($objConexion);
    $objUsuario->setIdUsuario($idUsuario);
    $objUsuario->deleteUsuario();
    $response = array('success' => true, 'message' => 'Usuario eliminado correctamente');
    echo json_encode($response);
    exit;
}

// Add Usuario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombreUsuario = $_POST['nombreUsuario'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $objConexion = new ConexionDB();
    $objUsuario = new Usuario($objConexion);
    $objUsuario->setNombreUsuario($nombreUsuario);
    $objUsuario->setEmail($email);
    $objUsuario->setPassword($password);
    $objUsuario->addUsuario();
    $response = array('success' => true, 'message' => 'Usuario añadido correctamente');
    echo json_encode($response);
    exit;
}

// Update Usuario
else if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $idUsuario = intval($data['idUsuario']);
    $nombreUsuario = $data['nombreUsuario'];
    $email = $data['email'];
    $password = $data['password'];
    $objConexion = new ConexionDB();
    $objUsuario = new Usuario($objConexion);
    $objUsuario->setIdUsuario($idUsuario);
    $objUsuario->setNombreUsuario($nombreUsuario);
    $objUsuario->setEmail($email);
    $objUsuario->setPassword($password);
    $objUsuario->updateUsuario();
    $response = array('success' => true, 'message' => 'Usuario actualizado correctamente');
    echo json_encode($response);
    exit;
}
?>
