<?php

class Dashboard extends Controllers
{
    public function __construct()
    {
        parent::__construct();

        requireLogin();
        getPermisos(1);
    }

    public function dashboard(): void
    {
        $data = [
            'page_id'           => 2,
            'page_tag'          => "Dashboard - " . NOMBRE_EMPRESA,
            'page_title'        => "Dashboard - " . NOMBRE_EMPRESA,
            'page_name'         => "dashboard",
            'page_functions_js' => "functions_dashboard.js"
        ];

        $this->views->getView($this, "dashboard", $data);
    }
}
