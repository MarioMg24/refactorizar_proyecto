<?php

include '../dataAccess/conexion/Conexion.php';
include '../dataAccess/dataAccessLogic/Usuario.php';


function sendResponse($data) {
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function sendError($message) {
    header('Content-Type: application/json');
    echo json_encode(array('success' => false, 'message' => $message));
    exit;
}


// Manejar solicitudes POST
// Manejar solicitudes POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['action'])) {
        $objConexion = new ConexionDB();
        $objUsuario = new Usuario($objConexion);

        if ($data['action'] == 'login') {
            $correo_electronico = $data['correo_electronico'];
            $contrasena = $data['contrasena'];

            $result = $objUsuario->loginUsuario($correo_electronico, $contrasena);

            if ($result) {
                session_start();
                $_SESSION['user'] = $result;
                sendResponse(array('success' => true, 'message' => 'Inicio de sesión exitoso'));
            } else {
                sendError('Correo electrónico o contraseña incorrectos');
            }
        }

        if ($data['action'] == 'register') {
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

            $objUsuario->setNombre($nombre);
            $objUsuario->setApellido($apellido);
            $objUsuario->setCorreoElectronico($correo_electronico);
            $objUsuario->setContraseña($contrasena);
            $objUsuario->setTelefono($telefono);
            $objUsuario->setDireccion($direccion);
            $objUsuario->setPerfil($perfil);

            $success = $objUsuario->addUsuario();
            sendResponse(array('success' => $success, 'message' => $success ? 'Usuario añadido correctamente' : 'Error al añadir usuario'));
        }

        if ($data['action'] == 'changePassword') {
            session_start();
            if (!isset($_SESSION['user']) || !is_array($_SESSION['user'])) {
                sendError('Usuario no autenticado o sesión inválida');
            }
        
            // Verificar si existe ID_Usuario, si no, buscar por correo electrónico
            if (isset($_SESSION['user']['ID_Usuario'])) {
                $idUsuario = $_SESSION['user']['ID_Usuario'];
            } elseif (isset($_SESSION['user']['Correo_electronico'])) {
                $correoElectronico = $_SESSION['user']['Correo_electronico'];
                $usuario = $objUsuario->readUsuarioByEmail($correoElectronico);
                if (!$usuario) {
                    sendError('No se pudo encontrar el usuario');
                }
                $idUsuario = $usuario['ID_usuario'];
            } else {
                sendError('Información de usuario incompleta en la sesión');
            }
        
            $nuevaContrasena = $data['nueva_contrasena'];
        
            $objUsuario->setIdUsuario($idUsuario);
            $objUsuario->setContraseña($nuevaContrasena);
        
            try {
                $success = $objUsuario->changePassword();
                if ($success) {
                    session_destroy();
                    sendResponse(array('success' => true, 'message' => 'Contraseña cambiada correctamente. Inicie sesión nuevamente.'));
                } else {
                    sendError('No se pudo cambiar la contraseña');
                }
            } catch (Exception $e) {
                sendError('Error al cambiar la contraseña: ' . $e->getMessage());
            }
        }
    }

    sendError('Acción no válida.');
}


// Manejar solicitudes GET
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $objConexion = new ConexionDB();
    $objUsuario = new Usuario($objConexion);

    if (isset($_GET['perfil']) && $_GET['perfil'] === 'true') {
        session_start();
        if (!isset($_SESSION['user']) || !is_array($_SESSION['user'])) {
            sendError('Usuario no autenticado o sesión inválida');
        }

        if (!isset($_SESSION['user']['ID_Usuario'])) {
            if (isset($_SESSION['user']['Correo_electronico'])) {
                $correoElectronico = $_SESSION['user']['Correo_electronico'];
                $usuario = $objUsuario->readUsuarioByEmail($correoElectronico);
            } else {
                sendError('Información de usuario incompleta en la sesión');
            }
        } else {
            $idUsuario = $_SESSION['user']['ID_Usuario'];
            $usuario = $objUsuario->readUsuarioById($idUsuario);
        }

        if (!$usuario) {
            sendError('No se pudo obtener la información del usuario');
        }

        unset($usuario['Contrasena']);
        sendResponse($usuario);
    }

    if (isset($_GET['id_usuario'])) {
        $idUsuario = intval($_GET['id_usuario']);
        $usuario = $objUsuario->readUsuarioById($idUsuario);
        sendResponse($usuario);
    }

    $array = $objUsuario->readUsuario();
    sendResponse($array);
}

// Manejar solicitudes DELETE
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $idUsuario = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($idUsuario <= 0) {
        sendError('ID de usuario no válido.');
    }

    $objConexion = new ConexionDB();
    $objUsuario = new Usuario($objConexion);
    $objUsuario->setIdUsuario($idUsuario);
    $objUsuario->deleteUsuario();
    sendResponse(array('success' => true, 'message' => 'Usuario eliminado correctamente'));
}

// Manejar solicitudes PUT
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
    sendResponse(array('success' => $success, 'message' => $success ? 'Usuario actualizado correctamente' : 'Error al actualizar usuario'));
}

sendError('Método HTTP no permitido.');
?>
