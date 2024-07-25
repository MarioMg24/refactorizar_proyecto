<?php
class Pedido {
    private $connectionDB;

    public function __construct($connectionDB) {
        $this->connectionDB = $connectionDB->conectar();
    }

    public function crearPedido($idUsuario, $total) {
        try {
            $sql = "INSERT INTO Pedido (ID_usuario, Total, Estado) VALUES (?, ?, 'Pendiente')";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($idUsuario, $total));
            return $this->connectionDB->lastInsertId();
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function agregarDetallePedido($idPedido, $idProducto, $cantidad, $precio) {
        try {
            $sql = "INSERT INTO DetallePedido (ID_pedido, ID_producto, Cantidad, Precio) VALUES (?, ?, ?, ?)";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($idPedido, $idProducto, $cantidad, $precio));
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
}
?>