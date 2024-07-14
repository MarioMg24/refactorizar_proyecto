<?php
include '../dataAccess/conexion/Conexion.php';
include '../dataAccess/dataAccessLogic/Usuario.php';

// Función para devolver errores JSON
function sendError($message) {
    $response = array('success' => false, 'message' => $message);
    echo json_encode($response);
    exit;
}

// Inicio de sesión
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['action']) && $data['action'] == 'login') {
        $correo_electronico = $data['correo_electronico'];
        $contrasena = $data['contrasena'];

        $objConexion = new ConexionDB();
        $objUsuario = new Usuario($objConexion);
        $result = $objUsuario->loginUsuario($correo_electronico, $contrasena);

        if ($result) {
            $response = array('success' => true, 'message' => 'Inicio de sesión exitoso');
        } else {
            $response = array('success' => false, 'message' => 'Correo electrónico o contraseña incorrectos');
        }
        echo json_encode($response);
        exit;
    }

    // Registro de usuario
    if (isset($data['action']) && $data['action'] == 'register') {
        $nombre = $data['nombre'];
        $apellido = $data['apellido'];
        $correo_electronico = $data['correo_electronico'];
        $contrasena = $data['contrasena'];
        $telefono = isset($data['telefono']) ? $data['telefono'] : '';
        $direccion = isset($data['direccion']) ? $data['direccion'] : '';
        $perfil = $data['perfil'];

        if (empty($nombre) || empty($apellido) || empty($correo_electronico) || empty($contrasena) || empty($perfil)) {
            sendError('Por favor, complete todos los campos obligatorios.');
        }

        // Verificación de correo electrónico único (opcional, dependiendo de tu aplicación)

        $objConexion = new ConexionDB();
        $objUsuario = new Usuario($objConexion);
        $objUsuario->setNombre($nombre);
        $objUsuario->setApellido($apellido);
        $objUsuario->setCorreoElectronico($correo_electronico);
        $objUsuario->setContraseña($contrasena);
        $objUsuario->setTelefono($telefono);
        $objUsuario->setDireccion($direccion);
        $objUsuario->setPerfil($perfil);

        $success = $objUsuario->addUsuario();
        $response = array('success' => $success, 'message' => $success ? 'Usuario añadido correctamente' : 'Error al añadir usuario');
        echo json_encode($response);
        exit;
    }

    // Si ninguna acción coincide
    sendError('Acción no válida.');
}

// Leer todos los usuarios
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $objConexion = new ConexionDB();
    $objUsuario = new Usuario($objConexion);
    $array = $objUsuario->readUsuario();
    echo json_encode($array);
    exit;
}

// Eliminar usuario
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $idUsuario = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    if ($idUsuario <= 0) {
        sendError('ID de usuario no válido.');
    }

    $objConexion = new ConexionDB();
    $objUsuario = new Usuario($objConexion);
    $objUsuario->setIdUsuario($idUsuario);
    $objUsuario->deleteUsuario();
    $response = array('success' => true, 'message' => 'Usuario eliminado correctamente');
    echo json_encode($response);
    exit;
}

// Actualizar usuario
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $idUsuario = isset($data['idUsuario']) ? intval($data['idUsuario']) : 0;

    if ($idUsuario <= 0) {
        sendError('ID de usuario no válido.');
    }

    $nombre = $data['nombre'];
    $apellido = $data['apellido'];
    $correo_electronico = $data['correo_electronico'];
    $contrasena = $data['contrasena'];
    $telefono = isset($data['telefono']) ? $data['telefono'] : '';
    $direccion = isset($data['direccion']) ? $data['direccion'] : '';
    $perfil = $data['perfil'];

    if (empty($nombre) || empty($apellido) || empty($correo_electronico) || empty($contrasena) || empty($perfil)) {
        sendError('Por favor, complete todos los campos obligatorios.');
    }

    $objConexion = new ConexionDB();
    $objUsuario = new Usuario($objConexion);
    $objUsuario->setIdUsuario($idUsuario);
    $objUsuario->setNombre($nombre);
    $objUsuario->setApellido($apellido);
    $objUsuario->setCorreoElectronico($correo_electronico);
    $objUsuario->setContraseña($contrasena);
    $objUsuario->setTelefono($telefono);
    $objUsuario->setDireccion($direccion);
    $objUsuario->setPerfil($perfil);

    $success = $objUsuario->updateUsuario();
    $response = array('success' => $success, 'message' => $success ? 'Usuario actualizado correctamente' : 'Error al actualizar usuario');
    echo json_encode($response);
    exit;
}

// Si ninguna solicitud coincide
sendError('Método HTTP no permitido.');
?>
