<?php

class RolesModel extends Mysql
{
    private int $intIdrol;
    private string $strRol;
    private string $strDescripcion;
    private int $intStatus;

    public function __construct()
    {
        parent::__construct();
    }

    public function selectRoles(): array
    {
        $whereAdmin = ($_SESSION['idUser'] ?? 0) != 1 ? " AND idrol != 1" : "";
        $sql = "SELECT * FROM rol WHERE status != 0" . $whereAdmin;
        return $this->select_all($sql);
    }

    public function selectRol(int $idrol): array
    {
        $sql = "SELECT * FROM rol WHERE idrol = ?";
        return $this->select($sql, [$idrol]);
    }

    public function insertRol(string $rol, string $descripcion, int $status): int|string
    {
        $sql = "SELECT idrol FROM rol WHERE nombrerol = ?";
        $exists = $this->select_all($sql, [$rol]);

        if (!empty($exists)) {
            return "exist";
        }

        $sql = "INSERT INTO rol (nombrerol, descripcion, status) VALUES (?, ?, ?)";
        return $this->insert($sql, [$rol, $descripcion, $status]);
    }

    public function updateRol(int $idrol, string $rol, string $descripcion, int $status): int|string
    {
        $sql = "SELECT idrol FROM rol WHERE nombrerol = ? AND idrol != ?";
        $exists = $this->select_all($sql, [$rol, $idrol]);

        if (!empty($exists)) {
            return "exist";
        }

        $sql = "UPDATE rol SET nombrerol = ?, descripcion = ?, status = ? WHERE idrol = ?";
        return $this->update($sql, [$rol, $descripcion, $status, $idrol]);
    }

    public function deleteRol(int $idrol): string
    {
        $sql = "SELECT idpersona FROM persona WHERE rolid = ?";
        $linkedUsers = $this->select_all($sql, [$idrol]);

        if (!empty($linkedUsers)) {
            return "exist";
        }

        $sql = "UPDATE rol SET status = 0 WHERE idrol = ?";
        $result = $this->update($sql, [$idrol]);

        return $result ? 'ok' : 'error';
    }
    
}
