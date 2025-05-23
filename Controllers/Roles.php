<?php

class Roles extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        requireLogin();
        getPermisos(2);
    }

    public function index(): void
    {
        $this->roles();
    }

    public function roles(): void
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header("Location: " . BASE_URL . "dashboard");
            exit;
        }

        $data = [
            'page_id'           => 3,
            'page_tag'          => "Roles Usuario",
            'page_name'         => "rol_usuario",
            'page_title'        => "Roles Usuario <small>" . NOMBRE_EMPRESA . "</small>",
            'page_functions_js' => "functions_roles.js"
        ];

        $this->views->getView($this, "roles", $data);
    }

    public function getRoles(): void
    {
        if (!$_SESSION['permisosMod']['r']) exit;

        $arrData = $this->model->selectRoles();

        foreach ($arrData as &$rol) {
            $rol['status'] = $rol['status'] == 1
                ? '<span class="badge badge-success">Activo</span>'
                : '<span class="badge badge-danger">Inactivo</span>';

            $btns = [];

            if ($_SESSION['permisosMod']['u']) {
                $btns[] = '<button class="btn btn-secondary btn-sm btnPermisosRol" onClick="fntPermisos(' . $rol['idrol'] . ')" title="Permisos"><i class="fas fa-key"></i></button>';
                $btns[] = '<button class="btn btn-primary btn-sm btnEditRol" onClick="fntEditRol(' . $rol['idrol'] . ')" title="Editar"><i class="fas fa-pencil-alt"></i></button>';
            }

            if ($_SESSION['permisosMod']['d']) {
                $btns[] = '<button class="btn btn-danger btn-sm btnDelRol" onClick="fntDelRol(' . $rol['idrol'] . ')" title="Eliminar"><i class="far fa-trash-alt"></i></button>';
            }

            $rol['options'] = '<div class="text-center">' . implode(' ', $btns) . '</div>';
        }

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function getSelectRoles(): void
    {
        $arrData = $this->model->selectRoles();
        $htmlOptions = '';

        foreach ($arrData as $rol) {
            if ($rol['status'] == 1) {
                $htmlOptions .= '<option value="' . $rol['idrol'] . '">' . $rol['nombrerol'] . '</option>';
            }
        }

        echo $htmlOptions;
        exit;
    }

    public function getRol(int $idrol): void
    {
        if (!$_SESSION['permisosMod']['r']) exit;

        $idrol = intval(strClean($idrol));
        if ($idrol <= 0) exit;

        $arrData = $this->model->selectRol($idrol);

        $arrResponse = empty($arrData)
            ? ['status' => false, 'msg' => 'Datos no encontrados.']
            : ['status' => true,  'data' => $arrData];

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function setRol(): void
    {
        if (!$_POST) exit;

        $intIdrol     = intval($_POST['idRol']);
        $strRol       = strClean($_POST['txtNombre']);
        $strDescripcion = strClean($_POST['txtDescripcion']);
        $intStatus    = intval($_POST['listStatus']);

        $request_rol = '';
        $option = $intIdrol === 0 ? 1 : 2;

        if ($option === 1 && $_SESSION['permisosMod']['w']) {
            $request_rol = $this->model->insertRol($strRol, $strDescripcion, $intStatus);
        }

        if ($option === 2 && $_SESSION['permisosMod']['u']) {
            $request_rol = $this->model->updateRol($intIdrol, $strRol, $strDescripcion, $intStatus);
        }

        if ($request_rol > 0) {
            $msg = $option === 1 ? 'Datos guardados correctamente.' : 'Datos actualizados correctamente.';
            $arrResponse = ['status' => true, 'msg' => $msg];
        } elseif ($request_rol === 'exist') {
            $arrResponse = ['status' => false, 'msg' => '¡Atención! El Rol ya existe.'];
        } else {
            $arrResponse = ['status' => false, 'msg' => 'No es posible almacenar los datos.'];
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function delRol(): void
    {
        if (!$_POST || !$_SESSION['permisosMod']['d']) exit;

        $intIdrol = intval($_POST['idrol']);
        $requestDelete = $this->model->deleteRol($intIdrol);

        if ($requestDelete === 'ok') {
            $arrResponse = ['status' => true, 'msg' => 'Se ha eliminado el Rol'];
        } elseif ($requestDelete === 'exist') {
            $arrResponse = ['status' => false, 'msg' => 'El Rol está asociado a usuarios.'];
        } else {
            $arrResponse = ['status' => false, 'msg' => 'Error al eliminar el Rol.'];
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
