<?php

class ClientesModel extends Mysql
{
    private int $intIdUsuario;
    private string $strIdentificacion;
    private string $strNombre;
    private string $strApellido;
    private int $intTelefono;
    private string $strEmail;
    private string $strPassword;
    private int $intTipoId;
    private string $strNit;
    private string $strNomFiscal;
    private string $strDirFiscal;

    public function __construct()
    {
        parent::__construct();
    }

    public function insertCliente(string $identificacion, string $nombre, string $apellido, int $telefono, string $email, string $password, int $tipoid, string $nit, string $nomFiscal, string $dirFiscal): mixed
    {
        $this->strIdentificacion = $identificacion;
        $this->strNombre = $nombre;
        $this->strApellido = $apellido;
        $this->intTelefono = $telefono;
        $this->strEmail = $email;
        $this->strPassword = $password;
        $this->intTipoId = $tipoid;
        $this->strNit = $nit;
        $this->strNomFiscal = $nomFiscal;
        $this->strDirFiscal = $dirFiscal;

        $sql = "SELECT * FROM persona WHERE email_user = ? OR identificacion = ?";
        $request = $this->select_all($sql, [$this->strEmail, $this->strIdentificacion]);

        if (empty($request)) {
            $query = "INSERT INTO persona (identificacion, nombres, apellidos, telefono, email_user, password, rolid, nit, nombrefiscal, direccionfiscal)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $arrData = [
                $this->strIdentificacion,
                $this->strNombre,
                $this->strApellido,
                $this->intTelefono,
                $this->strEmail,
                $this->strPassword,
                $this->intTipoId,
                $this->strNit,
                $this->strNomFiscal,
                $this->strDirFiscal
            ];
            return $this->insert($query, $arrData);
        }

        return 'exist';
    }

    public function selectClientes(): array
    {
        $sql = "SELECT idpersona, identificacion, nombres, apellidos, telefono, email_user, status
                FROM persona
                WHERE rolid = 7 AND status != 0";
        return $this->select_all($sql);
    }

    public function selectCliente(int $idpersona): array|null
    {
        $this->intIdUsuario = $idpersona;
        $sql = "SELECT idpersona, identificacion, nombres, apellidos, telefono, email_user, nit, nombrefiscal, direccionfiscal, status,
                       DATE_FORMAT(datecreated, '%d-%m-%Y') AS fechaRegistro
                FROM persona
                WHERE idpersona = ? AND rolid = 7";
        return $this->select($sql, [$this->intIdUsuario]);
    }

    public function updateCliente(int $idUsuario, string $identificacion, string $nombre, string $apellido, int $telefono, string $email, string $password, string $nit, string $nomFiscal, string $dirFiscal): mixed
    {
        $this->intIdUsuario = $idUsuario;
        $this->strIdentificacion = $identificacion;
        $this->strNombre = $nombre;
        $this->strApellido = $apellido;
        $this->intTelefono = $telefono;
        $this->strEmail = $email;
        $this->strPassword = $password;
        $this->strNit = $nit;
        $this->strNomFiscal = $nomFiscal;
        $this->strDirFiscal = $dirFiscal;

        $sql = "SELECT * FROM persona WHERE (email_user = ? AND idpersona != ?) OR (identificacion = ? AND idpersona != ?)";
        $params = [$this->strEmail, $this->intIdUsuario, $this->strIdentificacion, $this->intIdUsuario];
        $request = $this->select_all($sql, $params);

        if (empty($request)) {
            if (!empty($this->strPassword)) {
                $sql = "UPDATE persona SET identificacion=?, nombres=?, apellidos=?, telefono=?, email_user=?, password=?, nit=?, nombrefiscal=?, direccionfiscal=?
                        WHERE idpersona = ?";
                $arrData = [
                    $this->strIdentificacion,
                    $this->strNombre,
                    $this->strApellido,
                    $this->intTelefono,
                    $this->strEmail,
                    $this->strPassword,
                    $this->strNit,
                    $this->strNomFiscal,
                    $this->strDirFiscal,
                    $this->intIdUsuario
                ];
            } else {
                $sql = "UPDATE persona SET identificacion=?, nombres=?, apellidos=?, telefono=?, email_user=?, nit=?, nombrefiscal=?, direccionfiscal=?
                        WHERE idpersona = ?";
                $arrData = [
                    $this->strIdentificacion,
                    $this->strNombre,
                    $this->strApellido,
                    $this->intTelefono,
                    $this->strEmail,
                    $this->strNit,
                    $this->strNomFiscal,
                    $this->strDirFiscal,
                    $this->intIdUsuario
                ];
            }
            return $this->update($sql, $arrData);
        }

        return 'exist';
    }

    public function deleteCliente(int $idpersona): bool
    {
        $this->intIdUsuario = $idpersona;
        $sql = "UPDATE persona SET status = ? WHERE idpersona = ?";
        return (bool) $this->update($sql, [0, $this->intIdUsuario]);
    }
}
