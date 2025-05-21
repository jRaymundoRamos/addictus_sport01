<?php

require_once("Models/TCategoria.php");
require_once("Models/TProducto.php");

class Home extends Controllers
{
    use TCategoria, TProducto;

    public function __construct()
    {
        parent::__construct();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function home(): void
    {
        $data = [
            'page_tag'   => NOMBRE_EMPRESA,
            'page_title' => NOMBRE_EMPRESA,
            'page_name'  => NOMBRE_EMPRESA,
			'slider'     => $this->getCategoriasT(implode(',', CAT_SLIDER)),
			'banner'     => $this->getCategoriasT(implode(',', CAT_BANNER)),
            'productos'  => $this->getProductosT()
        ];

        $this->views->getView($this, 'home', $data);
    }
}
