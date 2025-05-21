<?php

class CategoriasModel extends Mysql
{
    private int $intIdcategoria;
    private string $strCategoria;
    private string $strDescripcion;
    private int $intStatus;
    private string $strPortada;
    private string $strRuta;

    public function __construct()
    {
        parent::__construct();
    }

    public function insertCategoria(string $nombre, string $descripcion, string $portada, string $ruta, int $status): mixed
    {
        $this->strCategoria   = $nombre;
        $this->strDescripcion = $descripcion;
        $this->strPortada     = $portada;
        $this->strRuta        = $ruta;
        $this->intStatus      = $status;

        $sql     = "SELECT * FROM categoria WHERE nombre = ?";
        $request = $this->select_all($sql, [$this->strCategoria]);

        if (empty($request)) {
            $query_insert     = "INSERT INTO categoria(nombre, descripcion, portada, ruta, status) VALUES (?, ?, ?, ?, ?)";
            $arrData          = [
                $this->strCategoria,
                $this->strDescripcion,
                $this->strPortada,
                $this->strRuta,
                $this->intStatus
            ];
            return $this->insert($query_insert, $arrData);
        }

        return "exist";
    }

    public function selectCategorias(): array
    {
        $sql = "SELECT * FROM categoria WHERE status != 0";
        return $this->select_all($sql);
    }

    public function selectCategoria(int $idcategoria): mixed
    {
        $this->intIdcategoria = $idcategoria;
        $sql = "SELECT * FROM categoria WHERE idcategoria = ?";
        return $this->select($sql, [$this->intIdcategoria]);
    }

    public function updateCategoria(int $idcategoria, string $categoria, string $descripcion, string $portada, string $ruta, int $status): mixed
    {
        $this->intIdcategoria = $idcategoria;
        $this->strCategoria   = $categoria;
        $this->strDescripcion = $descripcion;
        $this->strPortada     = $portada;
        $this->strRuta        = $ruta;
        $this->intStatus      = $status;

        $sql     = "SELECT * FROM categoria WHERE nombre = ? AND idcategoria != ?";
        $request = $this->select_all($sql, [$this->strCategoria, $this->intIdcategoria]);

        if (empty($request)) {
            $sql     = "UPDATE categoria SET nombre = ?, descripcion = ?, portada = ?, ruta = ?, status = ? WHERE idcategoria = ?";
            $arrData = [
                $this->strCategoria,
                $this->strDescripcion,
                $this->strPortada,
                $this->strRuta,
                $this->intStatus,
                $this->intIdcategoria
            ];
            return $this->update($sql, $arrData);
        }

        return "exist";
    }

    public function deleteCategoria(int $idcategoria): string
    {
        $this->intIdcategoria = $idcategoria;

        $sql     = "SELECT * FROM producto WHERE categoriaid = ?";
        $request = $this->select_all($sql, [$this->intIdcategoria]);

        if (empty($request)) {
            $sql     = "UPDATE categoria SET status = ? WHERE idcategoria = ?";
            $arrData = [0, $this->intIdcategoria];
            $result  = $this->update($sql, $arrData);
            return $result ? 'ok' : 'error';
        }

        return 'exist';
    }
}
