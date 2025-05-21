<?php

class Error extends Controllers
{
    public function __construct()
    {
        parent::__construct();
    }

    public function notFound(): void
    {
        $this->views->getView($this, "error");
    }
}

// Ejecutar directamente si este archivo se carga como fallback
$notFound = new Error();
$notFound->notFound();
