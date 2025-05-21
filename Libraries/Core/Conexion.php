<?php

class Conexion {
    private ?PDO $conect = null;

    public function __construct() {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        try {
            $this->conect = new PDO($dsn, DB_USER, DB_PASSWORD);
            $this->conect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Puedes lanzar excepción para que la maneje el controlador
            throw new Exception("Error de conexión: " . $e->getMessage());
        }
    }

    public function conect(): PDO {
        return $this->conect;
    }
}
