<?php

require_once("Libraries/Core/Mysql.php");

trait TCliente
{
    private Mysql $con;
    private int $intIdUsuario;
    private string $strNombre;
    private string $strApellido;
    private int $intTelefono;
    private string $strEmail;
    private string $strPassword;
    private int $intTipoId;
    private string $intIdTransaccion;

    /**
     * Inserta un nuevo cliente
     */
    public function insertCliente(string $nombre, string $apellido, int $telefono, string $email, string $password, int $tipoid): mixed
    {
        $this->con = new Mysql();
        $this->strNombre = $nombre;
        $this->strApellido = $apellido;
        $this->intTelefono = $telefono;
        $this->strEmail = $email;
        $this->strPassword = $password;
        $this->intTipoId = $tipoid;

        $sql = "SELECT idpersona FROM persona WHERE email_user = ?";
        $request = $this->con->select_all($sql, [$this->strEmail]);

        if (empty($request)) {
            $query = "INSERT INTO persona(nombres, apellidos, telefono, email_user, password, rolid) VALUES (?, ?, ?, ?, ?, ?)";
            $params = [
                $this->strNombre,
                $this->strApellido,
                $this->intTelefono,
                $this->strEmail,
                $this->strPassword,
                $this->intTipoId
            ];
            return $this->con->insert($query, $params);
        }

        return 'exist';
    }

    /**
     * Inserta o actualiza detalles temporales del carrito para el usuario
     */
    public function insertDetalleTemp(array $pedido): void
    {
        $this->con = new Mysql();
        $this->intIdUsuario = (int) $pedido['idcliente'];
        $this->intIdTransaccion = $pedido['idtransaccion'];
        $productos = $pedido['productos'];

        $sqlCheck = "SELECT idtemp FROM detalle_temp WHERE transaccionid = ? AND personaid = ?";
        $check = $this->con->select_all($sqlCheck, [$this->intIdTransaccion, $this->intIdUsuario]);

        if (!empty($check)) {
            $sqlDelete = "DELETE FROM detalle_temp WHERE transaccionid = ? AND personaid = ?";
            $this->con->delete($sqlDelete, [$this->intIdTransaccion, $this->intIdUsuario]);
        }

        $sqlInsert = "INSERT INTO detalle_temp(personaid, productoid, precio, cantidad, transaccionid) VALUES (?, ?, ?, ?, ?)";
        foreach ($productos as $producto) {
            $params = [
                $this->intIdUsuario,
                (int) $producto['idproducto'],
                (float) $producto['precio'],
                (int) $producto['cantidad'],
                $this->intIdTransaccion
            ];
            $this->con->insert($sqlInsert, $params);
        }
    }
}
