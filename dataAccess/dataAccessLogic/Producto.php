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

    // Setters
    public function setIdProducto($idProducto) {
        $this->idProducto = $idProducto;
    }

    public function setNombreProducto($nombreProducto) {
        $this->nombreProducto = $nombreProducto;
    }

    public function setDescripcionProducto($descripcionProducto) {
        $this->descripcionProducto = $descripcionProducto;
    }

    public function setPrecioProducto($precioProducto) {
        $this->precioProducto = $precioProducto;
    }

    public function setImagenProducto($imagenProducto) {
        $this->imagenProducto = $imagenProducto;
    }

    public function setCantidadDisponible($cantidadDisponible) {
        $this->cantidadDisponible = $cantidadDisponible;
    }

    public function setCategoriaId($categoriaId) {
        $this->categoriaId = $categoriaId;
    }

    public function setFechaVencimiento($fechaVencimiento) {
        $this->fechaVencimiento = $fechaVencimiento;
    }

    // Getters
    public function getIdProducto() {
        return $this->idProducto;
    }

    public function getNombreProducto() {
        return $this->nombreProducto;
    }

    public function getDescripcionProducto() {
        return $this->descripcionProducto;
    }

    public function getPrecioProducto() {
        return $this->precioProducto;
    }

    public function getImagenProducto() {
        return $this->imagenProducto;
    }

    public function getCantidadDisponible() {
        return $this->cantidadDisponible;
    }

    public function getCategoriaId() {
        return $this->categoriaId;
    }

    public function getFechaVencimiento() {
        return $this->fechaVencimiento;
    }

    // Add Producto
    public function addProducto(): bool {
        try {
            $sql = "INSERT INTO Producto (Nombre_producto, Descripcion, Precio, Imagen_producto, Cantidad_disponible, ID_categoria, Fecha_caducidad) VALUES (?, ?, ?, ?, ?, ?, ?)";
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
            $sql = "UPDATE Producto SET Nombre_producto=?, Descripcion=?, Precio=?, Imagen_producto=?, Cantidad_disponible=?, ID_categoria=?, Fecha_caducidad=? WHERE ID_producto=?";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array(
                $this->getNombreProducto(),
                $this->getDescripcionProducto(),
                $this->getPrecioProducto(),
                $this->getImagenProducto(),
                $this->getCantidadDisponible(),
                $this->getCategoriaId(),
                $this->getFechaVencimiento(),
                $this->getIdProducto()
            ));
            $count = $stmt->rowCount();
            return $count > 0;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    // Obtener productos por categoría
    public function getProductosByCategoria($idCategoria): array {
        try {
            $sql = "SELECT * FROM Producto WHERE ID_categoria = ?";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($idCategoria));
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $productos = $stmt->fetchAll();
            return $productos;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return [];
        }
    }

    public function getProductoByIdAndCategoria($idProducto, $idCategoria): array {
        try {
            $sql = "SELECT * FROM Producto WHERE ID_producto = ? AND ID_categoria = ?";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($idProducto, $idCategoria));
            $producto = $stmt->fetch(PDO::FETCH_ASSOC);
            return $producto ? $producto : [];
        } catch (PDOException $e) {
            echo $e->getMessage();
            return [];
        }
    }

    // Nuevo método para obtener todos los productos
    public function getAllProducts(): array {
        try {
            $sql = "SELECT ID_producto, Nombre_producto FROM Producto";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
            return [];
        }
    }

    // Eliminar registros en DetallePedido por producto
    public function deleteDetallePedidoByProducto($idProducto): bool {
        try {
            $sql = "DELETE FROM DetallePedido WHERE ID_producto = ?";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($idProducto));
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    // Eliminar registros en ProveedorProducto por producto
    public function deleteProveedorProductoByProducto($idProducto): bool {
        try {
            $sql = "DELETE FROM ProveedorProducto WHERE ID_producto = ?";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($idProducto));
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
}
?>