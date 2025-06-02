<?php

class PermisosModel extends Mysql
{
    private int $intRolid;
    private int $intModuloid;
    private int $r;
    private int $w;
    private int $u;
    private int $d;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Devuelve todos los módulos activos.
     */
    public function selectModulos(): array
    {
        $sql = "SELECT * FROM modulo WHERE status != 0";
        return $this->select_all($sql);
    }

    /**
     * Devuelve permisos asignados a un rol.
     */
    public function selectPermisosRol(int $idrol): array
    {
        $sql = "SELECT * FROM permisos WHERE rolid = ?";
        return $this->select_all_params($sql, [$idrol]);
    }

    /**
     * Elimina todos los permisos asociados a un rol.
     */
    public function deletePermisos(int $idrol): bool
    {
        $sql = "DELETE FROM permisos WHERE rolid = ?";
        return $this->delete($sql, [$idrol]);
    }

    /**
     * Inserta un nuevo permiso para un módulo y rol.
     */
    public function insertPermisos(int $idrol, int $idmodulo, int $r, int $w, int $u, int $d): int|false
    {
        $sql = "INSERT INTO permisos (rolid, moduloid, r, w, u, d) VALUES (?, ?, ?, ?, ?, ?)";
        $params = [$idrol, $idmodulo, $r, $w, $u, $d];
        return $this->insert($sql, $params);
    }

    /**
     * Devuelve todos los permisos de un rol con el nombre del módulo.
     */
    public function permisosModulo(int $idrol): array
    {
        $sql = "SELECT p.rolid,
                       p.moduloid,
                       m.titulo AS modulo,
                       p.r,
                       p.w,
                       p.u,
                       p.d
                FROM permisos p
                INNER JOIN modulo m ON p.moduloid = m.idmodulo
                WHERE p.rolid = ?";

        $result = $this->select_all_params($sql, [$idrol]);

        // Organizar permisos por id del módulo
        $arrPermisos = [];
        foreach ($result as $permiso) {
            $arrPermisos[$permiso['moduloid']] = $permiso;
        }

        return $arrPermisos;
    }
}
