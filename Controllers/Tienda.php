<?php

require_once("Models/TCategoria.php");
require_once("Models/TProducto.php");
require_once("Models/TCliente.php");
require_once("Models/LoginModel.php");

class Tienda extends Controllers
{
	use TCategoria, TProducto, TCliente;

	public $login;

	public function __construct()
	{
		parent::__construct();
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
		$this->login = new LoginModel();
	}

	public function index(): void
    {
        $this->tienda();
    }

	public function tienda(): void
	{
		$data = [
			'page_tag'    => NOMBRE_EMPRESA,
			'page_title'  => NOMBRE_EMPRESA,
			'page_name'   => "tienda",
			'productos'   => $this->getProductosT()
		];

		$this->views->getView($this, "tienda", $data);
	}

	public function categoria(string $params): void
	{
		if (empty($params)) {
			header("Location:" . BASE_URL);
			exit;
		}

		[$idcategoria, $ruta] = explode(",", $params);
		$infoCategoria = $this->getProductosCategoriaT((int)$idcategoria, strClean($ruta));

		$data = [
			'page_tag'    => NOMBRE_EMPRESA . " - " . $infoCategoria['categoria'],
			'page_title'  => $infoCategoria['categoria'],
			'page_name'   => "categoria",
			'productos'   => $infoCategoria['productos']
		];

		$this->views->getView($this, "categoria", $data);
	}

	public function producto(string $params): void
	{
		if (empty($params)) {
			header("Location:" . BASE_URL);
			exit;
		}

		[$idproducto, $ruta] = explode(",", $params);
		$infoProducto = $this->getProductoT((int)$idproducto, strClean($ruta));

		if (empty($infoProducto)) {
			header("Location:" . BASE_URL);
			exit;
		}

		$data = [
			'page_tag'   => NOMBRE_EMPRESA . " - " . $infoProducto['nombre'],
			'page_title' => $infoProducto['nombre'],
			'page_name'  => "producto",
			'producto'   => $infoProducto,
			'productos'  => $this->getProductosRandom($infoProducto['categoriaid'], 8, "r")
		];

		$this->views->getView($this, "producto", $data);
	}

	public function addCarrito(): void
	{
		if (!$_POST) exit;

		$idproducto = openssl_decrypt($_POST['id'], METHODENCRIPT, KEY);
		$cantidad = (int)$_POST['cant'];

		if (!is_numeric($idproducto) || $cantidad <= 0) {
			echo json_encode(['status' => false, 'msg' => 'Dato incorrecto.']);
			exit;
		}

		$infoProducto = $this->getProductoIDT($idproducto);
		if (empty($infoProducto)) {
			echo json_encode(['status' => false, 'msg' => 'Producto no existente.']);
			exit;
		}

		$producto = [
			'idproducto' => $idproducto,
			'producto'   => $infoProducto['nombre'],
			'cantidad'   => $cantidad,
			'precio'     => $infoProducto['precio'],
			'imagen'     => $infoProducto['images'][0]['url_image']
		];

		if (!isset($_SESSION['arrCarrito'])) {
			$_SESSION['arrCarrito'] = [];
		}

		$repetido = false;

		foreach ($_SESSION['arrCarrito'] as &$item) {
			if ($item['idproducto'] == $idproducto) {
				$item['cantidad'] += $cantidad;
				$repetido = true;
				break;
			}
		}

		if (!$repetido) {
			$_SESSION['arrCarrito'][] = $producto;
		}

		$cantCarrito = array_sum(array_column($_SESSION['arrCarrito'], 'cantidad'));
		$htmlCarrito = getFile('Template/Modals/modalCarrito', $_SESSION['arrCarrito']);

		echo json_encode([
			'status'       => true,
			'msg'          => '¡Se agregó al carrito!',
			'cantCarrito'  => $cantCarrito,
			'htmlCarrito'  => $htmlCarrito
		], JSON_UNESCAPED_UNICODE);
		exit;
	}

	public function delCarrito(): void
	{
		if (!$_POST) exit;

		$idproducto = openssl_decrypt($_POST['id'], METHODENCRIPT, KEY);
		$option     = (int)$_POST['option'];

		if (!is_numeric($idproducto) || !in_array($option, [1, 2])) {
			echo json_encode(['status' => false, 'msg' => 'Dato incorrecto.']);
			exit;
		}

		$arrCarrito = $_SESSION['arrCarrito'] ?? [];
		$cantCarrito = 0;
		$subtotal = 0;

		foreach ($arrCarrito as $index => $item) {
			if ($item['idproducto'] == $idproducto) {
				unset($arrCarrito[$index]);
			}
		}

		$_SESSION['arrCarrito'] = array_values($arrCarrito); // Reindexar array

		foreach ($_SESSION['arrCarrito'] as $pro) {
			$cantCarrito += $pro['cantidad'];
			$subtotal += $pro['cantidad'] * $pro['precio'];
		}

		$htmlCarrito = $option === 1 ? getFile('Template/Modals/modalCarrito', $_SESSION['arrCarrito']) : "";

		echo json_encode([
			'status'      => true,
			'msg'         => '¡Producto eliminado!',
			'cantCarrito' => $cantCarrito,
			'htmlCarrito' => $htmlCarrito,
			'subTotal'    => SMONEY . formatMoney($subtotal),
			'total'       => SMONEY . formatMoney($subtotal + COSTOENVIO)
		], JSON_UNESCAPED_UNICODE);
		exit;
	}

	public function updCarrito(): void
	{
		if (!$_POST) exit;

		$idproducto = openssl_decrypt($_POST['id'], METHODENCRIPT, KEY);
		$cantidad   = intval($_POST['cantidad']);

		if (!is_numeric($idproducto) || $cantidad <= 0) {
			echo json_encode(['status' => false, 'msg' => 'Dato incorrecto.']);
			exit;
		}

		$arrCarrito = $_SESSION['arrCarrito'] ?? [];
		$totalProducto = 0;
		$subtotal = 0;

		foreach ($arrCarrito as &$item) {
			if ($item['idproducto'] == $idproducto) {
				$item['cantidad'] = $cantidad;
				$totalProducto = $item['precio'] * $cantidad;
				break;
			}
		}

		$_SESSION['arrCarrito'] = $arrCarrito;

		foreach ($arrCarrito as $pro) {
			$subtotal += $pro['cantidad'] * $pro['precio'];
		}

		echo json_encode([
			'status'         => true,
			'msg'            => '¡Producto actualizado!',
			'totalProducto'  => SMONEY . formatMoney($totalProducto),
			'subTotal'       => SMONEY . formatMoney($subtotal),
			'total'          => SMONEY . formatMoney($subtotal + COSTOENVIO)
		], JSON_UNESCAPED_UNICODE);
		exit;
	}

	public function registro(): void
	{
		error_reporting(0);
		if (!$_POST) exit;

		if (
			empty($_POST['txtNombre']) ||
			empty($_POST['txtApellido']) ||
			empty($_POST['txtTelefono']) ||
			empty($_POST['txtEmailCliente'])
		) {
			echo json_encode(['status' => false, 'msg' => 'Datos incorrectos.']);
			exit;
		}

		$strNombre   = ucwords(strClean($_POST['txtNombre']));
		$strApellido = ucwords(strClean($_POST['txtApellido']));
		$intTelefono = intval(strClean($_POST['txtTelefono']));
		$strEmail    = strtolower(strClean($_POST['txtEmailCliente']));
		$intTipoId   = 7;
		$strPassword = passGenerator();
		$strPasswordEncript = hash("SHA256", $strPassword);

		$request_user = $this->insertCliente(
			$strNombre,
			$strApellido,
			$intTelefono,
			$strEmail,
			$strPasswordEncript,
			$intTipoId
		);

		if ($request_user > 0) {
			$nombreUsuario = $strNombre . ' ' . $strApellido;
			$dataUsuario = [
				'nombreUsuario' => $nombreUsuario,
				'email'         => $strEmail,
				'password'      => $strPassword,
				'asunto'        => 'Bienvenido a tu tienda en línea'
			];

			$_SESSION['idUser'] = $request_user;
			$_SESSION['login'] = true;
			$this->login->sessionLogin($request_user);
			// sendEmail($dataUsuario, 'email_bienvenida');

			$arrResponse = ['status' => true, 'msg' => 'Registro exitoso.'];
		} elseif ($request_user === 'exist') {
			$arrResponse = ['status' => false, 'msg' => 'El email ya existe.'];
		} else {
			$arrResponse = ['status' => false, 'msg' => 'No se pudo completar el registro.'];
		}

		echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		exit;
	}
}
