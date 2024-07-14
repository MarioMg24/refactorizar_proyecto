<?php
class Proveedor {
    private $connectionDB;
    private $idProveedor;
    private $nombreProveedor;
    private $contactoProveedor;
    private $terminosNegociacion;

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

    public function setTerminosNegociacion($terminosNegociacion) {
        $this->terminosNegociacion = $terminosNegociacion;
    }

    public function getTerminosNegociacion() {
        return $this->terminosNegociacion;
    }

    // Add Proveedor
    public function addProveedor() {
        try {
            $sql = "INSERT INTO Proveedor (Nombre_proveedor, Contacto, Terminos_negociacion) VALUES (?, ?, ?)";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($this->getNombreProveedor(), $this->getContactoProveedor(), $this->getTerminosNegociacion()));
            return $this->connectionDB->lastInsertId();
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
    
    public function addProveedorProducto($idProveedor, $idProducto) {
        try {
            $sql = "INSERT INTO ProveedorProducto (ID_proveedor, ID_producto) VALUES (?, ?)";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($idProveedor, $idProducto));
            return true;
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

    // Read Proveedor by ID
    public function readProveedorById($idProveedor): array {
        try {
            $sql = "SELECT * FROM Proveedor WHERE ID_proveedor = ?";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($idProveedor));
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $proveedor = $stmt->fetch();
            
            if ($proveedor) {
                // Fetch associated productos
                $sqlProductos = "SELECT pr.Nombre_producto FROM ProveedorProducto pp
                                 INNER JOIN Producto pr ON pp.ID_producto = pr.ID_producto
                                 WHERE pp.ID_proveedor = ?";
                $stmtProductos = $this->connectionDB->prepare($sqlProductos);
                $stmtProductos->execute(array($idProveedor));
                $stmtProductos->setFetchMode(PDO::FETCH_ASSOC);
                $productos = $stmtProductos->fetchAll();
                
                $proveedor['Productos'] = $productos;
            }

            return $proveedor ? $proveedor : [];
        } catch (PDOException $e) {
            echo $e->getMessage();
            return [];
        }
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
            $sql = "UPDATE Proveedor SET Nombre_proveedor=?, Contacto=?, Terminos_negociacion=? WHERE ID_proveedor=?";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($this->getNombreProveedor(), $this->getContactoProveedor(), $this->getTerminosNegociacion(), $this->getIdProveedor()));
            $count = $stmt->rowCount();
            return $count > 0;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    // Read Proveedores con Productos
    public function readProveedoresConProductos(): array {
        try {
            $sql = "SELECT p.ID_proveedor, p.Nombre_proveedor, p.Contacto, p.Terminos_negociacion, 
                    GROUP_CONCAT(pr.Nombre_producto SEPARATOR ',') AS Productos 
                    FROM Proveedor p
                    LEFT JOIN ProveedorProducto pp ON p.ID_proveedor = pp.ID_proveedor
                    LEFT JOIN Producto pr ON pp.ID_producto = pr.ID_producto
                    GROUP BY p.ID_proveedor";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $arrayQuery = $stmt->fetchAll();

            $proveedores = array();
            foreach ($arrayQuery as $row) {
                $productos = explode(",", $row['Productos']);
                $proveedores[] = array(
                    "ID_proveedor" => $row["ID_proveedor"],
                    "Nombre_proveedor" => $row["Nombre_proveedor"],
                    "Contacto" => $row["Contacto"],
                    "Terminos_negociacion" => $row["Terminos_negociacion"],
                    "Productos" => array_map(function ($producto) {
                        return array("Nombre_producto" => $producto);
                    }, $productos)
                );
            }
            return $proveedores;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return [];
    }

    // Delete ProveedorProducto relations
    public function deleteProveedorProductoRelations(): bool {
        try {
            $sql = "DELETE FROM ProveedorProducto WHERE ID_proveedor=?";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($this->getIdProveedor()));
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    // Delete all ProveedorProducto for a specific proveedor
    public function deleteAllProveedorProducto($idProveedor): bool {
        try {
            $sql = "DELETE FROM ProveedorProducto WHERE ID_proveedor=?";
            $stmt = $this->connectionDB->prepare($sql);
            $stmt->execute(array($idProveedor));
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
}
?>
