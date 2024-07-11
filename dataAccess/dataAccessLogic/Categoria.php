<?php
class Categoria {
    private $connectionDB;
    private $idCategoria;
    private $nombreCategoria;
    private $imagenCategoria;

    public function __construct($connectionDB) {
        $this->connectionDB = $connectionDB->conectar();
    }

    public function setIdCategoria($idCategoria) {
        $this->idCategoria = $idCategoria;
    }

    public function getIdCategoria() {
        return $this->idCategoria;
    }

    public function setNombreCategoria($nombreCategoria) {
        $this->nombreCategoria = $nombreCategoria;
    }

    public function getNombreCategoria() {
        return $this->nombreCategoria;
    }

    public function setImagenCategoria($imagenCategoria) {
        $this->imagenCategoria = $imagenCategoria;
    }

    public function getImagenCategoria() {
        return $this->imagenCategoria;
    }

    // Add Categoria
    public function addCategoria(): bool {
        try {
            $sql = "INSERT INTO Categoria (Nombre_categoria, Imagen_categoria) VALUES (?, ?)";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($this->getNombreCategoria(), $this->getImagenCategoria()));
            $count = $stmt->rowCount();
            return $count > 0;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    // Read Categoria
    public function readCategoria(): array {
        try {
            $sql = "SELECT * FROM Categoria";
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

    // Delete Categoria
    public function deleteCategoria(): bool {
        try {
            $sql = "DELETE FROM Categoria WHERE ID_categoria=?";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($this->getIdCategoria()));
            $count = $stmt->rowCount();
            return $count > 0;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    // Update Categoria
    public function updateCategoria(): bool {
        try {
            $sql = "UPDATE Categoria SET Nombre_categoria=?, Imagen_categoria=? WHERE ID_categoria=?";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($this->getNombreCategoria(), $this->getImagenCategoria(), $this->getIdCategoria()));
            $count = $stmt->rowCount();
            return $count > 0;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
}
?>
