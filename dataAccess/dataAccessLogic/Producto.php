<?php
class Producto {
    private $connectionDB;
    private $idProducto;
    private $nombreProducto;
    private $descripcionProducto;
    private $precioProducto;
    private $imagenProducto;
    private $cantidadDisponible;
    private $categoriaId;
    private $fechaVencimiento;

    public function __construct($connectionDB) {
        $this->connectionDB = $connectionDB->conectar();
    }

    public function setIdProducto($idProducto) {
        $this->idProducto = $idProducto;
    }

    public function getIdProducto() {
        return $this->idProducto;
    }

    public function setNombreProducto($nombreProducto) {
        $this->nombreProducto = $nombreProducto;
    }

    public function getNombreProducto() {
        return $this->nombreProducto;
    }

    public function setDescripcionProducto($descripcionProducto) {
        $this->descripcionProducto = $descripcionProducto;
    }

    public function getDescripcionProducto() {
        return $this->descripcionProducto;
    }

    public function setPrecioProducto($precioProducto) {
        $this->precioProducto = $precioProducto;
    }

    public function getPrecioProducto() {
        return $this->precioProducto;
    }

    public function setImagenProducto($imagenProducto) {
        $this->imagenProducto = $imagenProducto;
    }

    public function getImagenProducto() {
        return $this->imagenProducto;
    }

    public function setCantidadDisponible($cantidadDisponible) {
        $this->cantidadDisponible = $cantidadDisponible;
    }

    public function getCantidadDisponible() {
        return $this->cantidadDisponible;
    }

    public function setCategoriaId($categoriaId) {
        $this->categoriaId = $categoriaId;
    }

    public function getCategoriaId() {
        return $this->categoriaId;
    }

    public function setFechaVencimiento($fechaVencimiento) {
        $this->fechaVencimiento = $fechaVencimiento;
    }

    public function getFechaVencimiento() {
        return $this->fechaVencimiento;
    }

    // Add Producto
    public function addProducto(): bool {
        try {
            $sql = "INSERT INTO Producto (Nombre_producto, Descripcion_producto, Precio_producto, Imagen_producto, Cantidad_disponible, ID_categoria, Fecha_vencimiento) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($this->getNombreProducto(), $this->getDescripcionProducto(), $this->getPrecioProducto(), $this->getImagenProducto(), $this->getCantidadDisponible(), $this->getCategoriaId(), $this->getFechaVencimiento()));
            $count = $stmt->rowCount();
            return $count > 0;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    // Read Producto
    public function readProducto(): array {
        try {
            $sql = "SELECT * FROM Producto";
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

    // Delete Producto
    public function deleteProducto(): bool {
        try {
            $sql = "DELETE FROM Producto WHERE ID_producto=?";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($this->getIdProducto()));
            $count = $stmt->rowCount();
            return $count > 0;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    // Update Producto
    public function updateProducto(): bool {
        try {
            $sql = "UPDATE Producto SET Nombre_producto=?, Descripcion_producto=?, Precio_producto=?, Imagen_producto=?, Cantidad_disponible=?, ID_categoria=?, Fecha_vencimiento=? WHERE ID_producto=?";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($this->getNombreProducto(), $this->getDescripcionProducto(), $this->getPrecioProducto(), $this->getImagenProducto(), $this->getCantidadDisponible(), $this->getCategoriaId(), $this->getFechaVencimiento(), $this->getIdProducto()));
            $count = $stmt->rowCount();
            return $count > 0;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
}
?>
