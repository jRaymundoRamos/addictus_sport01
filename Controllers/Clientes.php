<?php

class Clientes extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        requireLogin();
        getPermisos(3);
    }

    public function clientes(): void
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header("Location: " . BASE_URL . "dashboard");
            exit;
        }

        $data = [
            'page_tag'          => 'Clientes',
            'page_title'        => 'CLIENTES <small>' . NOMBRE_EMPRESA . '</small>',
            'page_name'         => 'clientes',
            'page_functions_js' => 'functions_clientes.js'
        ];

        $this->views->getView($this, "clientes", $data);
    }

    public function setCliente(): void
    {
        if (!$_POST) exit;

        $requiredFields = [
            'txtIdentificacion', 'txtNombre', 'txtApellido', 'txtTelefono',
            'txtEmail', 'txtNit', 'txtNombreFiscal', 'txtDirFiscal'
        ];

        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                echo json_encode(['status' => false, 'msg' => 'Datos incorrectos.']);
                exit;
            }
        }

        $idUsuario       = intval($_POST['idUsuario']);
        $identificacion  = strClean($_POST['txtIdentificacion']);
        $nombre          = ucwords(strClean($_POST['txtNombre']));
        $apellido        = ucwords(strClean($_POST['txtApellido']));
        $telefono        = intval(strClean($_POST['txtTelefono']));
        $email           = strtolower(strClean($_POST['txtEmail']));
        $nit             = strClean($_POST['txtNit']);
        $nombreFiscal    = strClean($_POST['txtNombreFiscal']);
        $dirFiscal       = strClean($_POST['txtDirFiscal']);
        $tipoId          = 7;
        $option          = ($idUsuario === 0) ? 1 : 2;
        $request         = '';

        if ($option === 1 && $_SESSION['permisosMod']['w']) {
            $password          = empty($_POST['txtPassword']) ? passGenerator() : $_POST['txtPassword'];
            $passwordEncrypted = hash("SHA256", $password);

            $request = $this->model->insertCliente(
                $identificacion, $nombre, $apellido,
                $telefono, $email, $passwordEncrypted, $tipoId,
                $nit, $nombreFiscal, $dirFiscal
            );
        }

        if ($option === 2 && $_SESSION['permisosMod']['u']) {
            $password = empty($_POST['txtPassword']) ? '' : hash("SHA256", $_POST['txtPassword']);

            $request = $this->model->updateCliente(
                $idUsuario, $identificacion, $nombre, $apellido,
                $telefono, $email, $password, $nit, $nombreFiscal, $dirFiscal
            );
        }

        if ($request > 0) {
            if ($option === 1) {
                sendEmail([
                    'nombreUsuario' => "$nombre $apellido",
                    'email'         => $email,
                    'password'      => $password,
                    'asunto'        => 'Bienvenido a tu tienda en línea'
                ], 'email_bienvenida');
            }
            echo json_encode(['status' => true, 'msg' => 'Datos guardados correctamente.']);
        } elseif ($request === 'exist') {
            echo json_encode(['status' => false, 'msg' => 'El email o identificación ya existe.']);
        } else {
            echo json_encode(['status' => false, 'msg' => 'No se pudo almacenar la información.']);
        }

        exit;
    }

    public function getClientes(): void
    {
        if (!$_SESSION['permisosMod']['r']) exit;

        $arrData = $this->model->selectClientes();

        foreach ($arrData as &$cliente) {
            $btnView = $btnEdit = $btnDelete = '';

            if ($_SESSION['permisosMod']['r']) {
                $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo(' . $cliente['idpersona'] . ')" title="Ver cliente"><i class="far fa-eye"></i></button>';
            }

            if ($_SESSION['permisosMod']['u']) {
                $btnEdit = '<button class="btn btn-primary btn-sm" onClick="fntEditInfo(this,' . $cliente['idpersona'] . ')" title="Editar cliente"><i class="fas fa-pencil-alt"></i></button>';
            }

            if ($_SESSION['permisosMod']['d']) {
                $btnDelete = '<button class="btn btn-danger btn-sm" onClick="fntDelInfo(' . $cliente['idpersona'] . ')" title="Eliminar cliente"><i class="far fa-trash-alt"></i></button>';
            }

            $cliente['options'] = '<div class="text-center">' . $btnView . ' ' . $btnEdit . ' ' . $btnDelete . '</div>';
        }

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function getCliente(int $idpersona): void
    {
        if (!$_SESSION['permisosMod']['r']) exit;

        $id = intval($idpersona);
        if ($id <= 0) exit;

        $data = $this->model->selectCliente($id);
        $response = empty($data)
            ? ['status' => false, 'msg' => 'Datos no encontrados.']
            : ['status' => true, 'data' => $data];

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function delCliente(): void
    {
        if (!$_POST || !$_SESSION['permisosMod']['d']) exit;

        $id = intval($_POST['idUsuario']);
        $result = $this->model->deleteCliente($id);

        $response = $result
            ? ['status' => true, 'msg' => 'Se ha eliminado el cliente.']
            : ['status' => false, 'msg' => 'Error al eliminar el cliente.'];

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
}

?>
