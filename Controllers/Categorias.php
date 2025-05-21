<?php

class Categorias extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        requireLogin();
        getPermisos(6);
    }

    public function categorias(): void
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header("Location: " . BASE_URL . "dashboard");
            exit;
        }

        $data = [
            'page_tag'          => 'Categorias',
            'page_title'        => 'CATEGORIAS <small>' . NOMBRE_EMPRESA . '</small>',
            'page_name'         => 'categorias',
            'page_functions_js' => 'functions_categorias.js'
        ];

        $this->views->getView($this, "categorias", $data);
    }

    public function setCategoria(): void
    {
        if (!$_POST) exit;

        if (empty($_POST['txtNombre']) || empty($_POST['txtDescripcion']) || empty($_POST['listStatus'])) {
            echo json_encode(['status' => false, 'msg' => 'Datos incorrectos.']);
            exit;
        }

        $idCategoria   = intval($_POST['idCategoria']);
        $nombre        = strClean($_POST['txtNombre']);
        $descripcion   = strClean($_POST['txtDescripcion']);
        $status        = intval($_POST['listStatus']);
        $ruta          = str_replace(" ", "-", strtolower(clear_cadena($nombre)));

        $foto          = $_FILES['foto'] ?? null;
        $nombre_foto   = $foto['name'] ?? '';
        $imgPortada    = 'portada_categoria.png';
        if ($nombre_foto) {
            $imgPortada = 'img_' . md5(date('d-m-Y H:i:s')) . '.jpg';
        }

        $request = '';
        $option = $idCategoria === 0 ? 1 : 2;

        if ($option === 1 && $_SESSION['permisosMod']['w']) {
            $request = $this->model->inserCategoria($nombre, $descripcion, $imgPortada, $ruta, $status);
        }

        if ($option === 2 && $_SESSION['permisosMod']['u']) {
            if ($nombre_foto === '' && $_POST['foto_actual'] !== 'portada_categoria.png' && $_POST['foto_remove'] == 0) {
                $imgPortada = $_POST['foto_actual'];
            }
            $request = $this->model->updateCategoria($idCategoria, $nombre, $descripcion, $imgPortada, $ruta, $status);
        }

        if ($request > 0) {
            if ($nombre_foto !== '') uploadImage($foto, $imgPortada);

            if ($option === 2 && (
                ($nombre_foto === '' && $_POST['foto_remove'] == 1 && $_POST['foto_actual'] !== 'portada_categoria.png') ||
                ($nombre_foto !== '' && $_POST['foto_actual'] !== 'portada_categoria.png')
            )) {
                deleteFile($_POST['foto_actual']);
            }

            echo json_encode(['status' => true, 'msg' => 'Datos guardados correctamente.']);
        } elseif ($request === 'exist') {
            echo json_encode(['status' => false, 'msg' => '¡Atención! La categoría ya existe.']);
        } else {
            echo json_encode(['status' => false, 'msg' => 'No fue posible guardar los datos.']);
        }

        exit;
    }

    public function getCategorias(): void
    {
        if (!$_SESSION['permisosMod']['r']) exit;

        $arrData = $this->model->selectCategorias();

        foreach ($arrData as &$row) {
            $row['status'] = $row['status'] == 1
                ? '<span class="badge badge-success">Activo</span>'
                : '<span class="badge badge-danger">Inactivo</span>';

            $btnView = $btnEdit = $btnDelete = '';

            if ($_SESSION['permisosMod']['r']) {
                $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo(' . $row['idcategoria'] . ')" title="Ver categoría"><i class="far fa-eye"></i></button>';
            }

            if ($_SESSION['permisosMod']['u']) {
                $btnEdit = '<button class="btn btn-primary btn-sm" onClick="fntEditInfo(this,' . $row['idcategoria'] . ')" title="Editar categoría"><i class="fas fa-pencil-alt"></i></button>';
            }

            if ($_SESSION['permisosMod']['d']) {
                $btnDelete = '<button class="btn btn-danger btn-sm" onClick="fntDelInfo(' . $row['idcategoria'] . ')" title="Eliminar categoría"><i class="far fa-trash-alt"></i></button>';
            }

            $row['options'] = '<div class="text-center">' . $btnView . ' ' . $btnEdit . ' ' . $btnDelete . '</div>';
        }

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function getCategoria(int $idcategoria): void
    {
        if (!$_SESSION['permisosMod']['r']) exit;

        $id = intval($idcategoria);
        if ($id <= 0) exit;

        $data = $this->model->selectCategoria($id);
        $arrResponse = empty($data)
            ? ['status' => false, 'msg' => 'Datos no encontrados.']
            : ['status' => true, 'data' => array_merge($data, ['url_portada' => media() . '/images/uploads/' . $data['portada']])];

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function delCategoria(): void
    {
        if (!$_POST || !$_SESSION['permisosMod']['d']) exit;

        $id = intval($_POST['idCategoria']);
        $result = $this->model->deleteCategoria($id);

        $msg = match ($result) {
            'ok'    => ['status' => true, 'msg' => 'Se ha eliminado la categoría.'],
            'exist' => ['status' => false, 'msg' => 'No se puede eliminar una categoría con productos.'],
            default => ['status' => false, 'msg' => 'Error al eliminar la categoría.']
        };

        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function getSelectCategorias(): void
    {
        $htmlOptions = '';
        $arrData = $this->model->selectCategorias();

        foreach ($arrData as $cat) {
            if ($cat['status'] == 1) {
                $htmlOptions .= '<option value="' . $cat['idcategoria'] . '">' . $cat['nombre'] . '</option>';
            }
        }

        echo $htmlOptions;
        exit;
    }
}
