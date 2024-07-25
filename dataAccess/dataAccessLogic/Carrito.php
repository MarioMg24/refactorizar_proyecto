<?php
class Carrito {
    private $connectionDB;
    private $idCarrito;
    private $idUsuario;

    public function __construct($connectionDB) {
        $this->connectionDB = $connectionDB->conectar();
    }

    public function setIdCarrito($idCarrito) {
        $this->idCarrito = $idCarrito;
    }

    public function getIdCarrito() {
        return $this->idCarrito;
    }

    public function setIdUsuario($idUsuario) {
        $this->idUsuario = $idUsuario;
    }

    public function getIdUsuario() {
        return $this->idUsuario;
    }

    public function obtenerProductosEnCarrito() {
        try {
            $sql = "SELECT p.ID_producto, p.Nombre_producto, p.Precio, dc.Cantidad, p.Imagen_producto, p.Descripcion
                    FROM DetalleCarrito dc
                    JOIN Producto p ON dc.ID_producto = p.ID_producto
                    WHERE dc.ID_carrito = ?";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($this->getIdCarrito()));
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
            return [];
        }
    }

    public function agregarProductoAlCarrito($idProducto, $cantidad) {
        try {
            if ($this->productoYaEstaEnCarrito($idProducto)) {
                $sql = "UPDATE DetalleCarrito SET Cantidad = Cantidad + ? WHERE ID_carrito = ? AND ID_producto = ?";
                $stmt = $this->connectionDB->prepare($sql);
                $stmt->execute(array($cantidad, $this->getIdCarrito(), $idProducto));
            } else {
                $sql = "INSERT INTO DetalleCarrito (ID_carrito, ID_producto, Cantidad, Precio)
                        VALUES (?, ?, ?, (SELECT Precio FROM Producto WHERE ID_producto = ?))";
                $stmt = $this->connectionDB->prepare($sql);
                $stmt->execute(array($this->getIdCarrito(), $idProducto, $cantidad, $idProducto));
            }
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function quitarProductoDelCarrito($idProducto) {
        try {
            $sql = "DELETE FROM DetalleCarrito WHERE ID_carrito = ? AND ID_producto = ?";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($this->getIdCarrito(), $idProducto));
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function actualizarCantidadProducto($idProducto, $nuevaCantidad) {
        try {
            $sql = "UPDATE DetalleCarrito SET Cantidad = ?, Precio = (SELECT Precio FROM Producto WHERE ID_producto = ?) * ? WHERE ID_carrito = ? AND ID_producto = ?";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($nuevaCantidad, $idProducto, $nuevaCantidad, $this->getIdCarrito(), $idProducto));
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    private function productoYaEstaEnCarrito($idProducto) {
        try {
            $sql = "SELECT COUNT(*) FROM DetalleCarrito WHERE ID_carrito = ? AND ID_producto = ?";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($this->getIdCarrito(), $idProducto));
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function obtenerIdCarritoPorUsuario() {
        try {
            $sql = "SELECT ID_carrito FROM Carrito WHERE ID_usuario = ?";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($this->getIdUsuario()));
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
            return [];
        }
    }

    public function crearCarritoParaUsuario() {
        try {
            $sql = "INSERT INTO Carrito (ID_usuario) VALUES (?)";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($this->getIdUsuario()));
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
}
?>
