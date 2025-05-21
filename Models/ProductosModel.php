<?php

class ProductosModel extends Mysql
{
    private int $intIdProducto;
    private string $strNombre;
    private string $strDescripcion;
    private int $intCodigo;
    private int $intCategoriaId;
    private float $strPrecio;
    private int $intStock;
    private int $intStatus;
    private string $strRuta;
    private string $strImagen;

    public function __construct()
    {
        parent::__construct();
    }

    public function selectProductos(): array
    {
        $sql = "SELECT p.idproducto, p.codigo, p.nombre, p.descripcion, p.categoriaid,
                       c.nombre AS categoria, p.precio, p.stock, p.status
                FROM producto p
                INNER JOIN categoria c ON p.categoriaid = c.idcategoria
                WHERE p.status != 0";
        return $this->select_all($sql);
    }

    public function insertProducto(string $nombre, string $descripcion, int $codigo, int $categoriaid, string $precio, int $stock, string $ruta, int $status): int|string
    {
        $sql = "SELECT idproducto FROM producto WHERE codigo = ?";
        $exists = $this->select_all($sql, [$codigo]);

        if (!empty($exists)) {
            return "exist";
        }

        $sql = "INSERT INTO producto (categoriaid, codigo, nombre, descripcion, precio, stock, ruta, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $params = [$categoriaid, $codigo, $nombre, $descripcion, $precio, $stock, $ruta, $status];

        return $this->insert($sql, $params);
    }

    public function updateProducto(int $idproducto, string $nombre, string $descripcion, int $codigo, int $categoriaid, string $precio, int $stock, string $ruta, int $status): int|string
    {
        $sql = "SELECT idproducto FROM producto WHERE codigo = ? AND idproducto != ?";
        $exists = $this->select_all($sql, [$codigo, $idproducto]);

        if (!empty($exists)) {
            return "exist";
        }

        $sql = "UPDATE producto 
                SET categoriaid = ?, codigo = ?, nombre = ?, descripcion = ?, precio = ?, stock = ?, ruta = ?, status = ?
                WHERE idproducto = ?";
        $params = [$categoriaid, $codigo, $nombre, $descripcion, $precio, $stock, $ruta, $status, $idproducto];

        return $this->update($sql, $params);
    }

    public function selectProducto(int $idproducto): array
    {
        $sql = "SELECT p.idproducto, p.codigo, p.nombre, p.descripcion, p.precio, p.stock, p.categoriaid,
                       c.nombre AS categoria, p.status
                FROM producto p
                INNER JOIN categoria c ON p.categoriaid = c.idcategoria
                WHERE p.idproducto = ?";
        return $this->select($sql, [$idproducto]);
    }

    public function insertImage(int $idproducto, string $imagen): int|false
    {
        $sql = "INSERT INTO imagen (productoid, img) VALUES (?, ?)";
        return $this->insert($sql, [$idproducto, $imagen]);
    }

    public function selectImages(int $idproducto): array
    {
        $sql = "SELECT productoid, img FROM imagen WHERE productoid = ?";
        return $this->select_all($sql, [$idproducto]);
    }

    public function deleteImage(int $idproducto, string $imagen): bool
    {
        $sql = "DELETE FROM imagen WHERE productoid = ? AND img = ?";
        return $this->delete($sql, [$idproducto, $imagen]);
    }

    public function deleteProducto(int $idproducto): bool
    {
        $sql = "UPDATE producto SET status = 0 WHERE idproducto = ?";
        return $this->update($sql, [0, $idproducto]);
    }
}
