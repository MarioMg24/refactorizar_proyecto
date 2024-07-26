<?php
class Pedido {
    private $connectionDB;
    private $idPedido;
    private $idUsuario;
    private $total;
    private $estado;

    public function __construct($connectionDB) {
        $this->connectionDB = $connectionDB->conectar();
    }

    // Setters
    public function setIdPedido($idPedido) {
        $this->idPedido = $idPedido;
    }

    public function setIdUsuario($idUsuario) {
        $this->idUsuario = $idUsuario;
    }

    public function setTotal($total) {
        $this->total = $total;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    // Getters
    public function getIdPedido() {
        return $this->idPedido;
    }

    public function getIdUsuario() {
        return $this->idUsuario;
    }

    public function getTotal() {
        return $this->total;
    }

    public function getEstado() {
        return $this->estado;
    }

    // Crear un nuevo pedido
    public function crearPedido() {
        try {
            $sql = "INSERT INTO Pedido (ID_usuario, Total, Estado) VALUES (?, ?, ?)";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($this->getIdUsuario(), $this->getTotal(), $this->getEstado()));
            $this->setIdPedido($this->connectionDB->lastInsertId());
            return $this->getIdPedido();
        } catch (PDOException $e) {
            error_log("Error al crear pedido: " . $e->getMessage());
            return false;
        }
    }

    // Agregar detalle de pedido
    public function agregarDetallePedido($idProducto, $cantidad, $precio) {
        try {
            $sql = "INSERT INTO DetallePedido (ID_pedido, ID_producto, Cantidad, Precio) VALUES (?, ?, ?, ?)";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($this->getIdPedido(), $idProducto, $cantidad, $precio));
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error al agregar detalle de pedido: " . $e->getMessage());
            return false;
        }
    }

    // Obtener pedidos por usuario
    public function obtenerPedidosPorUsuario($idUsuario) {
        try {
            $sql = "SELECT * FROM Pedido WHERE ID_usuario = ?";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($idUsuario));
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener pedidos: " . $e->getMessage());
            return [];
        }
    }

    // Obtener todos los pedidos
    public function obtenerTodosLosPedidos() {
        try {
            $sql = "SELECT * FROM Pedido";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener todos los pedidos: " . $e->getMessage());
            return [];
        }
    }

    // Cancelar (eliminar) un pedido
    public function cancelarPedido($idPedido) {
        try {
            $sql = "DELETE FROM DetallePedido WHERE ID_pedido = ?";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($idPedido));

            $sql = "DELETE FROM Pedido WHERE ID_pedido = ?";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($idPedido));

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error al cancelar pedido: " . $e->getMessage());
            return false;
        }
    }
}
?>
