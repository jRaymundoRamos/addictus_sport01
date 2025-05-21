<?php

class HomeModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * [Opcional] Ejemplo de método para obtener categorías activas.
     * Descomenta y ajusta si lo necesitas.
     */
    /*
    public function getCategorias(): array
    {
        $sql = "SELECT * FROM categoria WHERE status != 0 ORDER BY nombre ASC";
        return $this->select_all($sql);
    }
    */
}
