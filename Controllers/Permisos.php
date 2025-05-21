<?php

class Permisos extends Controllers
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getPermisosRol(int $idrol): void
    {
        $rolid = intval($idrol);

        if ($rolid <= 0) {
            http_response_code(400);
            exit;
        }

        $arrModulos     = $this->model->selectModulos();
        $arrPermisosRol = $this->model->selectPermisosRol($rolid);
        $permisoDefault = ['r' => 0, 'w' => 0, 'u' => 0, 'd' => 0];

        foreach ($arrModulos as $i => $modulo) {
            $arrModulos[$i]['permisos'] = $arrPermisosRol[$i] ?? $permisoDefault;
        }

        $arrPermisoRol = [
            'idrol'   => $rolid,
            'modulos' => $arrModulos
        ];

        // Renderiza el modal directamente
        echo getModal("modalPermisos", $arrPermisoRol);

        exit;
    }

    public function setPermisos(): void
    {
        if ($_POST && isset($_POST['idrol'], $_POST['modulos']) && is_array($_POST['modulos'])) {
            $intIdrol = intval($_POST['idrol']);
            $modulos  = $_POST['modulos'];

            $this->model->deletePermisos($intIdrol);

            $success = false;

            foreach ($modulos as $modulo) {
                $idModulo = intval($modulo['idmodulo'] ?? 0);
                $r = !empty($modulo['r']) ? 1 : 0;
                $w = !empty($modulo['w']) ? 1 : 0;
                $u = !empty($modulo['u']) ? 1 : 0;
                $d = !empty($modulo['d']) ? 1 : 0;

                $success = $this->model->insertPermisos($intIdrol, $idModulo, $r, $w, $u, $d);
            }

            $arrResponse = $success
                ? ['status' => true,  'msg' => 'Permisos asignados correctamente.']
                : ['status' => false, 'msg' => 'No fue posible asignar los permisos.'];

            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }

        exit;
    }
}
