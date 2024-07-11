<?php
class Proveedor {
    private $connectionDB;
    private $idProveedor;
    private $nombreProveedor;
    private $contactoProveedor;

    public function __construct($connectionDB) {
        $this->connectionDB = $connectionDB->conectar();
    }

    public function setIdProveedor($idProveedor) {
        $this->idProveedor = $idProveedor;
    }

    public function getIdProveedor() {
        return $this->idProveedor;
    }

    public function setNombreProveedor($nombreProveedor) {
        $this->nombreProveedor = $nombreProveedor;
    }

    public function getNombreProveedor() {
        return $this->nombreProveedor;
    }

    public function setContactoProveedor($contactoProveedor) {
        $this->contactoProveedor = $contactoProveedor;
    }

    public function getContactoProveedor() {
        return $this->contactoProveedor;
    }

    // Add Proveedor
    public function addProveedor(): bool {
        try {
            $sql = "INSERT INTO Proveedor (Nombre_proveedor, Contacto) VALUES (?, ?)";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($this->getNombreProveedor(), $this->getContactoProveedor()));
            $count = $stmt->rowCount();
            return $count > 0;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    // Read Proveedor
    public function readProveedor(): array {
        try {
            $sql = "SELECT * FROM Proveedor";
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

    // Delete Proveedor
    public function deleteProveedor(): bool {
        try {
            $sql = "DELETE FROM Proveedor WHERE ID_proveedor=?";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($this->getIdProveedor()));
            $count = $stmt->rowCount();
            return $count > 0;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    // Update Proveedor
    public function updateProveedor(): bool {
        try {
            $sql = "UPDATE Proveedor SET Nombre_proveedor=?, Contacto=? WHERE ID_proveedor=?";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($this->getNombreProveedor(), $this->getContactoProveedor(), $this->getIdProveedor()));
            $count = $stmt->rowCount();
            return $count > 0;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
}
?>
