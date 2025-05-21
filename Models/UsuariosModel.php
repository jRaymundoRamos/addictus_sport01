<?php

class UsuariosModel extends Mysql
{
    private int $intIdUsuario;
    private string $strIdentificacion;
    private string $strNombre;
    private string $strApellido;
    private int $intTelefono;
    private string $strEmail;
    private string $strPassword;
    private int $intTipoId;
    private int $intStatus;
    private string $strNit;
    private string $strNomFiscal;
    private string $strDirFiscal;

    public function __construct()
    {
        parent::__construct();
    }

    public function insertUsuario(
        string $identificacion,
        string $nombre,
        string $apellido,
        int $telefono,
        string $email,
        string $password,
        int $tipoid,
        int $status
    ): mixed {
        $this->strIdentificacion = $identificacion;
        $this->strNombre = $nombre;
        $this->strApellido = $apellido;
        $this->intTelefono = $telefono;
        $this->strEmail = $email;
        $this->strPassword = $password;
        $this->intTipoId = $tipoid;
        $this->intStatus = $status;

        $sql = "SELECT * FROM persona WHERE email_user = ? OR identificacion = ?";
        $existente = $this->select_all($sql, [$email, $identificacion]);

        if (empty($existente)) {
            $query = "INSERT INTO persona (identificacion, nombres, apellidos, telefono, email_user, password, rolid, status) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $data = [
                $identificacion,
                $nombre,
                $apellido,
                $telefono,
                $email,
                $password,
                $tipoid,
                $status
            ];
            return $this->insert($query, $data);
        }

        return "exist";
    }

    public function selectUsuarios(): array
    {
        $whereAdmin = ($_SESSION['idUser'] != 1) ? " AND p.idpersona != 1" : "";
        $sql = "SELECT p.idpersona, p.identificacion, p.nombres, p.apellidos, p.telefono, 
                       p.email_user, p.status, r.idrol, r.nombrerol 
                FROM persona p
                INNER JOIN rol r ON p.rolid = r.idrol
                WHERE p.status != 0 $whereAdmin";

        return $this->select_all($sql);
    }

    public function selectUsuario(int $idpersona): array
    {
        $sql = "SELECT p.idpersona, p.identificacion, p.nombres, p.apellidos, p.telefono, p.email_user,
                       p.nit, p.nombrefiscal, p.direccionfiscal,
                       r.idrol, r.nombrerol, p.status, DATE_FORMAT(p.datecreated, '%d-%m-%Y') as fechaRegistro
                FROM persona p
                INNER JOIN rol r ON p.rolid = r.idrol
                WHERE p.idpersona = ?";

        return $this->select($sql, [$idpersona]);
    }

    public function updateUsuario(
        int $idUsuario,
        string $identificacion,
        string $nombre,
        string $apellido,
        int $telefono,
        string $email,
        string $password,
        int $tipoid,
        int $status
    ): mixed {
        $sql = "SELECT * FROM persona 
                WHERE (email_user = ? AND idpersona != ?) 
                   OR (identificacion = ? AND idpersona != ?)";
        $params = [$email, $idUsuario, $identificacion, $idUsuario];
        $existente = $this->select_all($sql, $params);

        if (!empty($existente)) return "exist";

        if (!empty($password)) {
            $sql = "UPDATE persona SET identificacion = ?, nombres = ?, apellidos = ?, telefono = ?, 
                                      email_user = ?, password = ?, rolid = ?, status = ?
                    WHERE idpersona = ?";
            $data = [$identificacion, $nombre, $apellido, $telefono, $email, $password, $tipoid, $status, $idUsuario];
        } else {
            $sql = "UPDATE persona SET identificacion = ?, nombres = ?, apellidos = ?, telefono = ?, 
                                      email_user = ?, rolid = ?, status = ?
                    WHERE idpersona = ?";
            $data = [$identificacion, $nombre, $apellido, $telefono, $email, $tipoid, $status, $idUsuario];
        }

        return $this->update($sql, $data);
    }

    public function deleteUsuario(int $idUsuario): bool
    {
        $sql = "UPDATE persona SET status = 0 WHERE idpersona = ?";
        return $this->update($sql, [$idUsuario]);
    }

    public function updatePerfil(
        int $idUsuario,
        string $identificacion,
        string $nombre,
        string $apellido,
        int $telefono,
        string $password = ""
    ): bool {
        if (!empty($password)) {
            $sql = "UPDATE persona SET identificacion = ?, nombres = ?, apellidos = ?, telefono = ?, password = ?
                    WHERE idpersona = ?";
            $data = [$identificacion, $nombre, $apellido, $telefono, $password, $idUsuario];
        } else {
            $sql = "UPDATE persona SET identificacion = ?, nombres = ?, apellidos = ?, telefono = ?
                    WHERE idpersona = ?";
            $data = [$identificacion, $nombre, $apellido, $telefono, $idUsuario];
        }

        return $this->update($sql, $data);
    }

    public function updateDataFiscal(int $idUsuario, string $nit, string $nomFiscal, string $dirFiscal): bool
    {
        $sql = "UPDATE persona SET nit = ?, nombrefiscal = ?, direccionfiscal = ? WHERE idpersona = ?";
        $data = [$nit, $nomFiscal, $dirFiscal, $idUsuario];
        return $this->update($sql, $data);
    }
}
