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

    // Read all Categorias
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
            return [];
        }
    }

    // Read Categoria by ID
    public function readCategoriaById($idCategoria): ?array {
        try {
            $sql = "SELECT * FROM Categoria WHERE ID_categoria = ?";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($idCategoria));
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $categoria = $stmt->fetch();
            return $categoria ?: null;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return null;
        }
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

    // Eliminar categoría y sus productos relacionados
    public function deleteCategoriaAndRelatedProducts(): bool {
        try {
            // Iniciar una transacción
            $this->connectionDB->beginTransaction();

            // Obtener productos relacionados con la categoría
            $producto = new Producto($this->connectionDB);
            $productos = $producto->getProductosByCategoria($this->getIdCategoria());

            // Eliminar los registros de DetallePedido y ProveedorProducto relacionados con cada producto
            foreach ($productos as $prod) {
                $producto->setIdProducto($prod['ID_producto']);
                $producto->deleteDetallePedidoByProducto($prod['ID_producto']);
                $producto->deleteProveedorProductoByProducto($prod['ID_producto']);
                $producto->deleteProducto();
            }

            // Eliminar la categoría
            $this->deleteCategoria();

            // Confirmar la transacción
            $this->connectionDB->commit();

            return true;
        } catch (PDOException $e) {
            // Revertir la transacción en caso de error
            $this->connectionDB->rollBack();
            echo $e->getMessage();
            return false;
        }
    }
}
?>
