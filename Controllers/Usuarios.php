<?php

class Usuarios extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        requireLogin();
        getPermisos(2);
    }

    public function usuarios(): void
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header("Location: " . BASE_URL . "dashboard");
            exit;
        }

        $data = [
            'page_tag'          => 'Usuarios',
            'page_title'        => 'USUARIOS <small>' . NOMBRE_EMPRESA . '</small>',
            'page_name'         => 'usuarios',
            'page_functions_js' => 'functions_usuarios.js'
        ];

        $this->views->getView($this, "usuarios", $data);
    }

    public function setUsuario(): void
    {
        if (!$_POST) exit;

        $required = ['txtIdentificacion', 'txtNombre', 'txtApellido', 'txtTelefono', 'txtEmail', 'listRolid', 'listStatus'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                echo json_encode(['status' => false, 'msg' => 'Datos incorrectos.']);
                exit;
            }
        }

        $idUsuario        = intval($_POST['idUsuario']);
        $strIdentificacion = strClean($_POST['txtIdentificacion']);
        $strNombre        = ucwords(strClean($_POST['txtNombre']));
        $strApellido      = ucwords(strClean($_POST['txtApellido']));
        $intTelefono      = intval(strClean($_POST['txtTelefono']));
        $strEmail         = strtolower(strClean($_POST['txtEmail']));
        $intTipoId        = intval(strClean($_POST['listRolid']));
        $intStatus        = intval(strClean($_POST['listStatus']));
        $strPassword      = empty($_POST['txtPassword']) ? "" : hash("SHA256", $_POST['txtPassword']);

        $request_user = "";
        $option = $idUsuario === 0 ? 1 : 2;

        if ($option === 1 && $_SESSION['permisosMod']['w']) {
            $strPassword = $strPassword ?: hash("SHA256", passGenerator());
            $request_user = $this->model->insertUsuario($strIdentificacion, $strNombre, $strApellido, $intTelefono, $strEmail, $strPassword, $intTipoId, $intStatus);
        }

        if ($option === 2 && $_SESSION['permisosMod']['u']) {
            $request_user = $this->model->updateUsuario($idUsuario, $strIdentificacion, $strNombre, $strApellido, $intTelefono, $strEmail, $strPassword, $intTipoId, $intStatus);
        }

        if ($request_user > 0) {
            $msg = $option === 1 ? 'Datos guardados correctamente.' : 'Datos actualizados correctamente.';
            $arrResponse = ['status' => true, 'msg' => $msg];
        } elseif ($request_user === 'exist') {
            $arrResponse = ['status' => false, 'msg' => '¡Atención! El email o la identificación ya existe.'];
        } else {
            $arrResponse = ['status' => false, 'msg' => 'No fue posible guardar los datos.'];
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function getUsuarios(): void
    {
        if (!$_SESSION['permisosMod']['r']) exit;

        $arrData = $this->model->selectUsuarios();
        foreach ($arrData as &$row) {
            $row['status'] = $row['status'] == 1
                ? '<span class="badge badge-success">Activo</span>'
                : '<span class="badge badge-danger">Inactivo</span>';

            $btnView = $btnEdit = $btnDelete = '';

            if ($_SESSION['permisosMod']['r']) {
                $btnView = '<button class="btn btn-info btn-sm btnViewUsuario" onClick="fntViewUsuario(' . $row['idpersona'] . ')" title="Ver usuario"><i class="far fa-eye"></i></button>';
            }

            if ($_SESSION['permisosMod']['u']) {
                $allowEdit = ($_SESSION['idUser'] == 1 && $_SESSION['userData']['idrol'] == 1)
                          || ($_SESSION['userData']['idrol'] == 1 && $row['idrol'] != 1);

                $btnEdit = $allowEdit
                    ? '<button class="btn btn-primary btn-sm btnEditUsuario" onClick="fntEditUsuario(this,' . $row['idpersona'] . ')" title="Editar usuario"><i class="fas fa-pencil-alt"></i></button>'
                    : '<button class="btn btn-secondary btn-sm" disabled><i class="fas fa-pencil-alt"></i></button>';
            }

            if ($_SESSION['permisosMod']['d']) {
                $allowDelete = (
                    ($_SESSION['idUser'] == 1 && $_SESSION['userData']['idrol'] == 1)
                    || ($_SESSION['userData']['idrol'] == 1 && $row['idrol'] != 1)
                ) && $_SESSION['userData']['idpersona'] != $row['idpersona'];

                $btnDelete = $allowDelete
                    ? '<button class="btn btn-danger btn-sm btnDelUsuario" onClick="fntDelUsuario(' . $row['idpersona'] . ')" title="Eliminar usuario"><i class="far fa-trash-alt"></i></button>'
                    : '<button class="btn btn-secondary btn-sm" disabled><i class="far fa-trash-alt"></i></button>';
            }

            $row['options'] = '<div class="text-center">' . $btnView . ' ' . $btnEdit . ' ' . $btnDelete . '</div>';
        }

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function getUsuario(int $idpersona): void
    {
        if (!$_SESSION['permisosMod']['r']) exit;

        $idusuario = intval($idpersona);
        if ($idusuario <= 0) exit;

        $arrData = $this->model->selectUsuario($idusuario);
        $arrResponse = empty($arrData)
            ? ['status' => false, 'msg' => 'Datos no encontrados.']
            : ['status' => true, 'data' => $arrData];

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function delUsuario(): void
    {
        if (!$_POST || !$_SESSION['permisosMod']['d']) exit;

        $intIdpersona = intval($_POST['idUsuario']);
        $result = $this->model->deleteUsuario($intIdpersona);

        $arrResponse = $result
            ? ['status' => true, 'msg' => 'Se ha eliminado el usuario.']
            : ['status' => false, 'msg' => 'Error al eliminar el usuario.'];

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function perfil(): void
    {
        $data = [
            'page_tag'          => 'Perfil',
            'page_title'        => 'Perfil de usuario',
            'page_name'         => 'perfil',
            'page_functions_js' => 'functions_usuarios.js'
        ];

        $this->views->getView($this, "perfil", $data);
    }

    public function putPerfil(): void
    {
        if (!$_POST) exit;

        $required = ['txtIdentificacion', 'txtNombre', 'txtApellido', 'txtTelefono'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                echo json_encode(['status' => false, 'msg' => 'Datos incorrectos.']);
                exit;
            }
        }

        $idUsuario = $_SESSION['idUser'];
        $strIdentificacion = strClean($_POST['txtIdentificacion']);
        $strNombre  = strClean($_POST['txtNombre']);
        $strApellido = strClean($_POST['txtApellido']);
        $intTelefono = intval(strClean($_POST['txtTelefono']));
        $strPassword = !empty($_POST['txtPassword']) ? hash("SHA256", $_POST['txtPassword']) : "";

        $request_user = $this->model->updatePerfil($idUsuario, $strIdentificacion, $strNombre, $strApellido, $intTelefono, $strPassword);

        $arrResponse = $request_user
            ? ['status' => true, 'msg' => 'Datos actualizados correctamente.']
            : ['status' => false, 'msg' => 'No es posible actualizar los datos.'];

        if ($request_user) sessionUser($idUsuario);

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function putDFical(): void
    {
        if (!$_POST) exit;

        $required = ['txtNit', 'txtNombreFiscal', 'txtDirFiscal'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                echo json_encode(['status' => false, 'msg' => 'Datos incorrectos.']);
                exit;
            }
        }

        $idUsuario = $_SESSION['idUser'];
        $strNit = strClean($_POST['txtNit']);
        $strNomFiscal = strClean($_POST['txtNombreFiscal']);
        $strDirFiscal = strClean($_POST['txtDirFiscal']);

        $request = $this->model->updateDataFiscal($idUsuario, $strNit, $strNomFiscal, $strDirFiscal);

        $arrResponse = $request
            ? ['status' => true, 'msg' => 'Datos actualizados correctamente.']
            : ['status' => false, 'msg' => 'No fue posible actualizar los datos.'];

        if ($request) sessionUser($idUsuario);

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
