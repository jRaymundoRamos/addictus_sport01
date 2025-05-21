<?php

class LoginModel extends Mysql
{
	private int $intIdUsuario;
	private string $strUsuario;
	private string $strPassword;
	private string $strToken;

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Verifica si el usuario y contraseña coinciden.
	 */
	public function loginUser(string $usuario, string $password): array|false
	{
		$this->strUsuario = $usuario;
		$this->strPassword = $password;

		$sql = "SELECT idpersona, status 
                FROM persona 
                WHERE email_user = ? AND password = ? AND status != 0";

		return $this->select($sql, [$this->strUsuario, $this->strPassword]);
	}

	/**
	 * Carga los datos de sesión del usuario con su rol.
	 */
	public function sessionLogin(int $iduser): array
	{
		$this->intIdUsuario = $iduser;

		$sql = "SELECT p.idpersona, p.identificacion, p.nombres, p.apellidos,
                       p.telefono, p.email_user, p.nit, p.nombrefiscal,
                       p.direccionfiscal, r.idrol, r.nombrerol, p.status
                FROM persona p
                INNER JOIN rol r ON p.rolid = r.idrol
                WHERE p.idpersona = ?";

		$userData = $this->select($sql, [$this->intIdUsuario]);
		$_SESSION['userData'] = $userData;

		return $userData;
	}

	/**
	 * Obtiene usuario por correo electrónico.
	 */
	public function getUserEmail(string $email): array|false
	{
		$this->strUsuario = $email;

		$sql = "SELECT idpersona, nombres, apellidos, status 
                FROM persona 
                WHERE email_user = ? AND status = 1";

		return $this->select($sql, [$this->strUsuario]);
	}

	/**
	 * Guarda el token de recuperación de contraseña.
	 */
	public function setTokenUser(int $idpersona, string $token): bool
	{
		$this->intIdUsuario = $idpersona;
		$this->strToken = $token;

		$sql = "UPDATE persona SET token = ? WHERE idpersona = ?";
		return $this->update($sql, [$this->strToken, $this->intIdUsuario]);
	}

	/**
	 * Verifica si el token y email son válidos.
	 */
	public function getUsuario(string $email, string $token): array|false
	{
		$this->strUsuario = $email;
		$this->strToken = $token;

		$sql = "SELECT idpersona 
                FROM persona 
                WHERE email_user = ? AND token = ? AND status = 1";

		return $this->select($sql, [$this->strUsuario, $this->strToken]);
	}

	/**
	 * Establece una nueva contraseña y elimina el token.
	 */
	public function insertPassword(int $idPersona, string $password): bool
	{
		$this->intIdUsuario = $idPersona;
		$this->strPassword = $password;

		$sql = "UPDATE persona SET password = ?, token = '' WHERE idpersona = ?";
		return $this->update($sql, [$this->strPassword, $this->intIdUsuario]);
	}
}
