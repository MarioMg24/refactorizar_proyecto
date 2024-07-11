<?php
class ConexionDB {
    private $server = "localhost:3308";
    private $database = "inventario";
    private $username = "root";
    private $password = "";
    private $conexion;

    public function conectar() {
        try {
            // Crear conexion
            $this->conexion = new PDO("mysql:host=$this->server;dbname=$this->database", $this->username, $this->password);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conexion; 
        } catch(PDOException $e) {
            echo "Connexion fallida: " . $e->getMessage();
            return null;
        }
    }

    public function cerrarConexion() {
        // Cerrar conexion
        $this->conexion = null;  
    }
}
?>
