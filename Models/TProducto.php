<?php
require_once("Libraries/Core/Mysql.php");

trait TProducto
{
    private Mysql $con;

    public function getProductosT(): array
    {
        $this->con = new Mysql();
        $sql = "SELECT p.idproducto, p.codigo, p.nombre, p.descripcion, p.categoriaid,
                       c.nombre AS categoria, p.precio, p.ruta, p.stock
                FROM producto p
                INNER JOIN categoria c ON p.categoriaid = c.idcategoria
                WHERE p.status != 0
                ORDER BY p.idproducto DESC";

        $productos = $this->con->select_all($sql);

        foreach ($productos as &$producto) {
            $producto['images'] = $this->getImagenesProducto((int) $producto['idproducto']);
        }

        return $productos;
    }

    public function getProductosCategoriaT(int $idcategoria, string $ruta): array
    {
        $this->con = new Mysql();

        $sqlCat = "SELECT idcategoria, nombre FROM categoria WHERE idcategoria = ?";
        $categoriaInfo = $this->con->select($sqlCat, [$idcategoria]);

        if (empty($categoriaInfo)) return [];

        $sql = "SELECT p.idproducto, p.codigo, p.nombre, p.descripcion, p.categoriaid,
                       c.nombre AS categoria, p.precio, p.ruta, p.stock
                FROM producto p
                INNER JOIN categoria c ON p.categoriaid = c.idcategoria
                WHERE p.status != 0 AND p.categoriaid = ? AND c.ruta = ?";

        $productos = $this->con->select_all($sql, [$idcategoria, $ruta]);

        foreach ($productos as &$producto) {
            $producto['images'] = $this->getImagenesProducto((int) $producto['idproducto']);
        }

        return [
            'idcategoria' => $categoriaInfo['idcategoria'],
            'categoria'   => $categoriaInfo['nombre'],
            'productos'   => $productos
        ];
    }

    public function getProductoT(int $idproducto, string $ruta): array
    {
        $this->con = new Mysql();
        $sql = "SELECT p.idproducto, p.codigo, p.nombre, p.descripcion, p.categoriaid,
                       c.nombre AS categoria, c.ruta AS ruta_categoria, p.precio, p.ruta, p.stock
                FROM producto p
                INNER JOIN categoria c ON p.categoriaid = c.idcategoria
                WHERE p.status != 0 AND p.idproducto = ? AND p.ruta = ?";

        $producto = $this->con->select($sql, [$idproducto, $ruta]);

        if (!empty($producto)) {
            $producto['images'] = $this->getImagenesProducto((int) $producto['idproducto'], true);
        }

        return $producto ?? [];
    }

    public function getProductosRandom(int $idcategoria, int $cantidad, string $orden): array
    {
        $this->con = new Mysql();

        $ordenSQL = match ($orden) {
            'r' => 'RAND()',
            'a' => 'idproducto ASC',
            default => 'idproducto DESC'
        };

        $sql = "SELECT p.idproducto, p.codigo, p.nombre, p.descripcion, p.categoriaid,
                       c.nombre AS categoria, p.precio, p.ruta, p.stock
                FROM producto p
                INNER JOIN categoria c ON p.categoriaid = c.idcategoria
                WHERE p.status != 0 AND p.categoriaid = ?
                ORDER BY $ordenSQL
                LIMIT $cantidad";

        $productos = $this->con->select_all($sql, [$idcategoria]);

        foreach ($productos as &$producto) {
            $producto['images'] = $this->getImagenesProducto((int) $producto['idproducto']);
        }

        return $productos;
    }

    public function getProductoIDT(int $idproducto): array
    {
        $this->con = new Mysql();
        $sql = "SELECT p.idproducto, p.codigo, p.nombre, p.descripcion, p.categoriaid,
                       c.nombre AS categoria, p.precio, p.ruta, p.stock
                FROM producto p
                INNER JOIN categoria c ON p.categoriaid = c.idcategoria
                WHERE p.status != 0 AND p.idproducto = ?";

        $producto = $this->con->select($sql, [$idproducto]);

        if (!empty($producto)) {
            $producto['images'] = $this->getImagenesProducto((int) $producto['idproducto'], true);
        }

        return $producto ?? [];
    }

    private function getImagenesProducto(int $idproducto, bool $fallbackDefault = false): array
    {
        $sqlImg = "SELECT img FROM imagen WHERE productoid = ?";
        $imagenes = $this->con->select_all($sqlImg, [$idproducto]);

        if (empty($imagenes) && $fallbackDefault) {
            return [['url_image' => media() . '/images/uploads/product.png']];
        }

        foreach ($imagenes as &$img) {
            $img['url_image'] = media() . '/images/uploads/' . $img['img'];
        }

        return $imagenes;
    }
}
