<?php

class Mysql extends Conexion {
    private PDO $conexion;
    private string $strquery = '';
    private array $arrValues = [];

    public function __construct() {
        $this->conexion = (new Conexion())->conect();
    }

    // Insertar un registro
    public function insert(string $query, array $arrValues): int {
        try {
            $this->strquery = $query;
            $this->arrValues = $arrValues;

            $insert = $this->conexion->prepare($this->strquery);
            $resInsert = $insert->execute($this->arrValues);
            return $resInsert ? (int)$this->conexion->lastInsertId() : 0;
        } catch (PDOException $e) {
            // Puedes loguearlo si lo deseas
            return 0;
        }
    }

    // Buscar un solo registro
    public function select(string $query): array {
        try {
            $this->strquery = $query;
            $result = $this->conexion->prepare($this->strquery);
            $result->execute();
            return $result->fetch(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            return [];
        }
    }

    // Devolver todos los registros
    public function select_all(string $query): array {
        try {
            $this->strquery = $query;
            $result = $this->conexion->prepare($this->strquery);
            $result->execute();
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Actualizar registros
    public function update(string $query, array $arrValues): bool {
        try {
            $this->strquery = $query;
            $this->arrValues = $arrValues;
            $update = $this->conexion->prepare($this->strquery);
            return $update->execute($this->arrValues);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Eliminar registros
    public function delete(string $query): bool {
        try {
            $this->strquery = $query;
            $result = $this->conexion->prepare($this->strquery);
            return $result->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
