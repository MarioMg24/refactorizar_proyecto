<?php
class Usuario {
    private $connectionDB;
    private $idUsuario;
    private $nombre;
    private $apellido;
    private $correo_electronico;
    private $contraseña;
    private $telefono;
    private $direccion;
    private $perfil;

    public function __construct($connectionDB) {
        $this->connectionDB = $connectionDB->conectar();
    }

    public function setIdUsuario($idUsuario) {
        $this->idUsuario = $idUsuario;
    }

    public function getIdUsuario() {
        return $this->idUsuario;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setApellido($apellido) {
        $this->apellido = $apellido;
    }

    public function getApellido() {
        return $this->apellido;
    }

    public function setCorreoElectronico($correo_electronico) {
        $this->correo_electronico = $correo_electronico;
    }

    public function getCorreoElectronico() {
        return $this->correo_electronico;
    }

    public function setContraseña($contraseña) {
        $this->contraseña = $contraseña;
    }

    public function getContraseña() {
        return $this->contraseña;
    }

    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    public function getDireccion() {
        return $this->direccion;
    }

    public function setPerfil($perfil) {
        $this->perfil = $perfil;
    }

    public function getPerfil() {
        return $this->perfil;
    }

    // Add Usuario
    public function addUsuario(): bool {
        try {
            $sql = "INSERT INTO Usuario (Nombre, Apellido, Correo_electronico, Contraseña, Telefono, Direccion, Perfil) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($this->getNombre(), $this->getApellido(), $this->getCorreoElectronico(), $this->getContraseña(), $this->getTelefono(), $this->getDireccion(), $this->getPerfil()));
            $count = $stmt->rowCount();
            return $count > 0;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    // Read Usuario
    public function readUsuario(): array {
        try {
            $sql = "SELECT * FROM Usuario";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $arrayQuery = $stmt->fetchAll();
            return $arrayQuery;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return [];
    }

    public function readUsuarioById($idUsuario): array {
        try {
            $sql = "SELECT * FROM Usuario WHERE ID_usuario = ?";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($idUsuario));
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $usuario = $stmt->fetch();
            return $usuario ? $usuario : [];
        } catch (PDOException $e) {
            echo $e->getMessage();
            return [];
        }
    }

    // Delete Usuario
    public function deleteUsuario(): bool {
        try {
            $sql = "DELETE FROM Usuario WHERE ID_usuario=?";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($this->getIdUsuario()));
            $count = $stmt->rowCount();
            return $count > 0;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function updateUsuario(): bool {
        try {
            $sql = "UPDATE Usuario SET Nombre=?, Apellido=?, Correo_electronico=?, Telefono=?, Direccion=?, Perfil=? WHERE ID_usuario=?";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($this->getNombre(), $this->getApellido(), $this->getCorreoElectronico(), $this->getTelefono(), $this->getDireccion(), $this->getPerfil(), $this->getIdUsuario()));
            $count = $stmt->rowCount();
            return $count > 0;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function loginUsuario($correo_electronico, $contrasena) {
        try {
            $sql = "SELECT * FROM Usuario WHERE Correo_electronico = ? AND Contraseña = ?";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($correo_electronico, $contrasena));
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user ? $user : false;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function readUsuarioByEmail($correoElectronico) {
        try {
            $sql = "SELECT * FROM Usuario WHERE Correo_electronico = ?";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($correoElectronico));
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $usuario = $stmt->fetch();
            return $usuario ? $usuario : [];
        } catch (PDOException $e) {
            echo $e->getMessage();
            return [];
        }
    }

    
}
?>
