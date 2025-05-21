<?php
require_once("Models/TCategoria.php");
require_once("Models/TProducto.php");
require_once("Models/TTipoPago.php");
require_once("Models/TCliente.php");

class Carrito extends Controllers
{
    use TCategoria, TProducto, TTipoPago, TCliente;

    public function __construct()
    {
        parent::__construct();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function carrito(): void
    {
        $data = [
            'page_tag'      => NOMBRE_EMPRESA . ' - Carrito',
            'page_title'    => 'Carrito de compras',
            'page_name'     => 'carrito'
        ];
        $this->views->getView($this, "carrito", $data);
    }

    public function procesarpago(): void
    {
        if (empty($_SESSION['arrCarrito'])) {
            header("Location: " . BASE_URL);
            exit;
        }

        if (isset($_SESSION['login'])) {
            $this->setDetalleTemp();
        }

        $data = [
            'page_tag'      => NOMBRE_EMPRESA . ' - Procesar Pago',
            'page_title'    => 'Procesar Pago',
            'page_name'     => 'procesarpago',
            'tiposPago'     => $this->getTiposPagoT()
        ];
        $this->views->getView($this, "procesarpago", $data);
    }

    private function setDetalleTemp(): void
    {
        $sid = session_id();
        $arrPedido = [
            'idcliente'     => $_SESSION['idUser'],
            'idtransaccion' => $sid,
            'productos'     => $_SESSION['arrCarrito']
        ];

        $this->insertDetalleTemp($arrPedido);
    }
}
