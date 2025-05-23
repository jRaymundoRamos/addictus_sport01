<?php

class Login extends Controllers
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_regenerate_id(true);

        if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
            header('Location: ' . BASE_URL . 'dashboard');
            exit;
        }

        parent::__construct();
    }
    public function index(): void
    {
        $this->login();
    }

    public function login(): void
    {
        $data = [
            'page_tag'          => "Login - " . NOMBRE_EMPRESA,
            'page_title'        => NOMBRE_EMPRESA,
            'page_name'         => "login",
            'page_functions_js' => "functions_login.js"
        ];

        $this->views->getView($this, "login", $data);
    }

    public function loginUser(): void
    {
        $rolesModel = new RolesModel();
        if ($_POST) {
            if (empty($_POST['txtEmail']) || empty($_POST['txtPassword'])) {
                $arrResponse = ['status' => false, 'msg' => 'Error de datos'];
            } else {
                $strUsuario = strtolower(strClean($_POST['txtEmail']));
                $strPassword = hash("SHA256", $_POST['txtPassword']);

                $requestUser = $this->model->loginUser($strUsuario, $strPassword);

                if (empty($requestUser)) {
                    $arrResponse = ['status' => false, 'msg' => 'El usuario o la contraseña es incorrecto.'];
                } else {
                    $arrData = $requestUser;
                    if ((int)$arrData['status'] === 1) {
                        $_SESSION['idUser'] = $arrData['idpersona'];
                        $_SESSION['login'] = true;

                        $_SESSION['userData'] = $this->model->sessionLogin($_SESSION['idUser']);
                        $_SESSION['permisos'] = $rolesModel->permisosRol($_SESSION['userData']['idrol']);

                        $arrResponse = ['status' => true, 'msg' => 'ok'];
                    } else {
                        $arrResponse = ['status' => false, 'msg' => 'Usuario inactivo.'];
                    }
                }
            }

            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        exit;
    }

    public function resetPass(): void
    {
        if ($_POST) {
            if (empty($_POST['txtEmailReset'])) {
                $arrResponse = ['status' => false, 'msg' => 'Error de datos'];
            } else {
                $token = token();
                $strEmail = strtolower(strClean($_POST['txtEmailReset']));
                $arrData = $this->model->getUserEmail($strEmail);

                if (empty($arrData)) {
                    $arrResponse = ['status' => false, 'msg' => 'Usuario no existente.'];
                } else {
                    $idpersona = $arrData['idpersona'];
                    $nombreUsuario = $arrData['nombres'] . ' ' . $arrData['apellidos'];
                    $url_recovery = BASE_URL . 'login/confirmUser/' . $strEmail . '/' . $token;

                    if ($this->model->setTokenUser($idpersona, $token)) {
                        $dataUsuario = [
                            'nombreUsuario' => $nombreUsuario,
                            'email'         => $strEmail,
                            'asunto'        => 'Recuperar cuenta - ' . NOMBRE_REMITENTE,
                            'url_recovery'  => $url_recovery
                        ];

                        $sendEmail = sendEmail($dataUsuario, 'email_cambioPassword');

                        $arrResponse = $sendEmail
                            ? ['status' => true, 'msg' => 'Se ha enviado un email para cambiar tu contraseña.']
                            : ['status' => false, 'msg' => 'No se pudo enviar el correo. Intenta más tarde.'];
                    } else {
                        $arrResponse = ['status' => false, 'msg' => 'No se pudo generar el token.'];
                    }
                }

                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            }
        }
        exit;
    }

    public function confirmUser(string $params): void
    {
        if (empty($params)) {
            header('Location: ' . BASE_URL);
            exit;
        }

        $arrParams = explode(',', $params);
        $strEmail = strClean($arrParams[0]);
        $strToken = strClean($arrParams[1]);

        $arrResponse = $this->model->getUsuario($strEmail, $strToken);

        if (empty($arrResponse)) {
            header("Location: " . BASE_URL);
            exit;
        }

        $data = [
            'page_tag'          => "Cambiar contraseña",
            'page_name'         => "cambiar_contrasenia",
            'page_title'        => "Cambiar Contraseña",
            'email'             => $strEmail,
            'token'             => $strToken,
            'idpersona'         => $arrResponse['idpersona'],
            'page_functions_js' => "functions_login.js"
        ];

        $this->views->getView($this, "cambiar_password", $data);
        exit;
    }

    public function setPassword(): void
    {
        if (
            empty($_POST['idUsuario']) || empty($_POST['txtEmail']) || empty($_POST['txtToken']) ||
            empty($_POST['txtPassword']) || empty($_POST['txtPasswordConfirm'])
        ) {
            $arrResponse = ['status' => false, 'msg' => 'Error de datos'];
        } else {
            $intIdpersona = (int)$_POST['idUsuario'];
            $strEmail     = strClean($_POST['txtEmail']);
            $strToken     = strClean($_POST['txtToken']);
            $pass1        = $_POST['txtPassword'];
            $pass2        = $_POST['txtPasswordConfirm'];

            if ($pass1 !== $pass2) {
                $arrResponse = ['status' => false, 'msg' => 'Las contraseñas no son iguales.'];
            } else {
                $arrUser = $this->model->getUsuario($strEmail, $strToken);
                if (empty($arrUser)) {
                    $arrResponse = ['status' => false, 'msg' => 'Error de datos.'];
                } else {
                    $hashedPass = hash("SHA256", $pass1);
                    $updated = $this->model->insertPassword($intIdpersona, $hashedPass);

                    $arrResponse = $updated
                        ? ['status' => true, 'msg' => 'Contraseña actualizada con éxito.']
                        : ['status' => false, 'msg' => 'No fue posible actualizar la contraseña.'];
                }
            }
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
