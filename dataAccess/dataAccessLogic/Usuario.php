<?php
class Usuario {
    private $connectionDB;
    private $idUsuario;
    private $nombreUsuario;
    private $email;
    private $password;

    public function __construct($connectionDB) {
        $this->connectionDB = $connectionDB->conectar();
    }

    public function setIdUsuario($idUsuario) {
        $this->idUsuario = $idUsuario;
    }

    public function getIdUsuario() {
        return $this->idUsuario;
    }

    public function setNombreUsuario($nombreUsuario) {
        $this->nombreUsuario = $nombreUsuario;
    }

    public function getNombreUsuario() {
        return $this->nombreUsuario;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getPassword() {
        return $this->password;
    }

    // Add Usuario
    public function addUsuario(): bool {
        try {
            $sql = "INSERT INTO Usuario (Nombre_usuario, Email, Password) VALUES (?, ?, ?)";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($this->getNombreUsuario(), $this->getEmail(), $this->getPassword()));
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

    // Update Usuario
    public function updateUsuario(): bool {
        try {
            $sql = "UPDATE Usuario SET Nombre_usuario=?, Email=?, Password=? WHERE ID_usuario=?";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($this->getNombreUsuario(), $this->getEmail(), $this->getPassword(), $this->getIdUsuario()));
            $count = $stmt->rowCount();
            return $count > 0;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
}
?>
