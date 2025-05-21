<?php

class Productos extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        requireLogin();
        getPermisos(4);
    }

    public function productos(): void
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header("Location: " . BASE_URL . "dashboard");
            exit;
        }

        $data = [
            'page_tag'          => "Productos",
            'page_title'        => "PRODUCTOS <small>" . NOMBRE_EMPRESA . "</small>",
            'page_name'         => "productos",
            'page_functions_js' => "functions_productos.js"
        ];

        $this->views->getView($this, "productos", $data);
    }

    public function getProductos(): void
    {
        if (!$_SESSION['permisosMod']['r']) exit;

        $arrData = $this->model->selectProductos();

        foreach ($arrData as &$item) {
            $item['status'] = $item['status'] == 1
                ? '<span class="badge badge-success">Activo</span>'
                : '<span class="badge badge-danger">Inactivo</span>';

            $item['precio'] = SMONEY . ' ' . formatMoney($item['precio']);

            $buttons = [];

            if ($_SESSION['permisosMod']['r']) {
                $buttons[] = '<button class="btn btn-info btn-sm" onClick="fntViewInfo(' . $item['idproducto'] . ')" title="Ver"><i class="far fa-eye"></i></button>';
            }
            if ($_SESSION['permisosMod']['u']) {
                $buttons[] = '<button class="btn btn-primary btn-sm" onClick="fntEditInfo(this,' . $item['idproducto'] . ')" title="Editar"><i class="fas fa-pencil-alt"></i></button>';
            }
            if ($_SESSION['permisosMod']['d']) {
                $buttons[] = '<button class="btn btn-danger btn-sm" onClick="fntDelInfo(' . $item['idproducto'] . ')" title="Eliminar"><i class="far fa-trash-alt"></i></button>';
            }

            $item['options'] = '<div class="text-center">' . implode(' ', $buttons) . '</div>';
        }

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function setProducto(): void
    {
        if (!$_POST) exit;

        if (
            empty($_POST['txtNombre']) || empty($_POST['txtCodigo']) ||
            empty($_POST['listCategoria']) || empty($_POST['txtPrecio']) ||
            empty($_POST['listStatus'])
        ) {
            $arrResponse = ['status' => false, 'msg' => 'Datos incorrectos.'];
        } else {
            $idProducto     = intval($_POST['idProducto']);
            $strNombre      = strClean($_POST['txtNombre']);
            $strDescripcion = strClean($_POST['txtDescripcion']);
            $strCodigo      = strClean($_POST['txtCodigo']);
            $intCategoriaId = intval($_POST['listCategoria']);
            $strPrecio      = strClean($_POST['txtPrecio']);
            $intStock       = intval($_POST['txtStock']);
            $intStatus      = intval($_POST['listStatus']);
            $ruta           = str_replace(" ", "-", strtolower(clear_cadena($strNombre)));

            $request_producto = '';
            $option = $idProducto === 0 ? 1 : 2;

            if ($option === 1 && $_SESSION['permisosMod']['w']) {
                $request_producto = $this->model->insertProducto($strNombre, $strDescripcion, $strCodigo, $intCategoriaId, $strPrecio, $intStock, $ruta, $intStatus);
            }

            if ($option === 2 && $_SESSION['permisosMod']['u']) {
                $request_producto = $this->model->updateProducto($idProducto, $strNombre, $strDescripcion, $strCodigo, $intCategoriaId, $strPrecio, $intStock, $ruta, $intStatus);
            }

            if ($request_producto > 0) {
                $arrResponse = [
                    'status'     => true,
                    'idproducto' => $option === 1 ? $request_producto : $idProducto,
                    'msg'        => $option === 1 ? 'Datos guardados correctamente.' : 'Datos actualizados correctamente.'
                ];
            } elseif ($request_producto === 'exist') {
                $arrResponse = ['status' => false, 'msg' => 'Ya existe un producto con ese código.'];
            } else {
                $arrResponse = ['status' => false, 'msg' => 'No fue posible almacenar los datos.'];
            }
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function getProducto(int $idproducto): void
    {
        if (!$_SESSION['permisosMod']['r']) exit;

        $idproducto = intval($idproducto);
        if ($idproducto <= 0) exit;

        $arrData = $this->model->selectProducto($idproducto);

        if (empty($arrData)) {
            $arrResponse = ['status' => false, 'msg' => 'Datos no encontrados.'];
        } else {
            $arrImg = $this->model->selectImages($idproducto);
            foreach ($arrImg as &$img) {
                $img['url_image'] = media() . '/images/uploads/' . $img['img'];
            }

            $arrData['images'] = $arrImg;
            $arrResponse = ['status' => true, 'data' => $arrData];
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function setImage(): void
    {
        if (!$_POST || empty($_POST['idproducto'])) exit;

        $idProducto = intval($_POST['idproducto']);
        $foto       = $_FILES['foto'];
        $imgNombre  = 'pro_' . md5(date('Y-m-d H:i:s')) . '.jpg';

        $request_image = $this->model->insertImage($idProducto, $imgNombre);

        $arrResponse = $request_image
            ? ['status' => true, 'imgname' => $imgNombre, 'msg' => 'Archivo cargado.']
            : ['status' => false, 'msg' => 'Error de carga.'];

        if ($request_image) uploadImage($foto, $imgNombre);

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function delFile(): void
    {
        if (!$_POST || empty($_POST['idproducto']) || empty($_POST['file'])) exit;

        $idProducto = intval($_POST['idproducto']);
        $imgNombre  = strClean($_POST['file']);

        $request = $this->model->deleteImage($idProducto, $imgNombre);

        $arrResponse = $request
            ? ['status' => true, 'msg' => 'Archivo eliminado']
            : ['status' => false, 'msg' => 'Error al eliminar'];

        if ($request) deleteFile($imgNombre);

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function delProducto(): void
    {
        if (!$_POST || !$_SESSION['permisosMod']['d']) exit;

        $intIdproducto = intval($_POST['idProducto']);
        $requestDelete = $this->model->deleteProducto($intIdproducto);

        $arrResponse = $requestDelete
            ? ['status' => true, 'msg' => 'Producto eliminado.']
            : ['status' => false, 'msg' => 'No se pudo eliminar el producto.'];

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
