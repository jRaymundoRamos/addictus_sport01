<?php

require_once("Libraries/Core/Mysql.php");

trait TCategoria
{
    private Mysql $con;

    public function getCategoriasT(string $categorias): array
    {
        $this->con = new Mysql();

        // Validación de entrada para evitar inyección SQL
        $ids = array_filter(array_map('intval', explode(',', $categorias)));
        if (empty($ids)) {
            return [];
        }

        // Se genera una cadena segura con los IDs
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "SELECT idcategoria, nombre, descripcion, portada, ruta 
                FROM categoria 
                WHERE status != 0 AND idcategoria IN ($placeholders)";

        $request = $this->con->select_all($sql, $ids);

        // Agregar ruta absoluta a las imágenes
        foreach ($request as &$categoria) {
            $categoria['portada'] = BASE_URL . '/Assets/images/uploads/' . $categoria['portada'];
        }

        return $request;
    }
}
