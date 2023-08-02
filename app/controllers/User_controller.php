<?php
class Usarios
{
    protected $Connection;
    protected $Connectiondb;

    function __construct()
    {
        $this->Connection = new Connection();
        $this->Connectiondb = $this->Connection->connect();
    }

    function Añadir($Nombre, $Apellido, $Tipo, $Contraseña, $Fn, $Documento, $Telefono, $Correo, $Direccion)
    {
        try {
            $query  = 'SELECT * FROM tipousuarios WHERE tipo = :Tipo';
            $query = $this->Connectiondb->prepare($query);
            $query->bindParam(':Tipo', $Tipo);
            $query->execute();
            $arrDatos = $query->fetchObject();

            //Iniciar una transacción, desactivando autocommit
            $this->Connectiondb->beginTransaction();
            $query = 'INSERT INTO usuarios (nombre, apellido, id_tpusuarios, pass, f_nacimiento, docuemnto, telefono, correo, direccion) VALUES (:Nombre, :Apellido, :Tipo,:Pass, :Fn, :Docuemento, :Telefono, :Correo, :Dirreccion)';

            //Ejecuta una sentencia
            $sql = $this->Connectiondb->prepare($query);

            $sql->bindParam(':Nombre', $Nombre);
            $sql->bindParam(':Apellido', $Apellido);
            $sql->bindParam(':Tipo', $arrDatos->id_tipo);
            $sql->bindParam(':Fn', $Fn);
            $sql->bindParam(':Documento', $Documento);
            $sql->bindParam(':Telefono', $Telefono);
            $sql->bindParam(':Correo', $Correo);
            $sql->bindParam(':Direccion', $Direccion);

            $passwordHash = md5($Contraseña);
            $sql->bindParam(':Pass', $passwordHash);

            //ejecucion del SQL
            $sql->execute();

            //Consignar los cambios
            $this->Connectiondb->commit();

            echo "Se ha añadido con exito.";
        } catch (PDOException $e) {
            //Reconocer un error y revertir los cambios
            $this->Connectiondb->rollBack();

            echo 'Se Presento El Siguiente Error: ' . $e;
        }
    }
    function Mostrar()
    {
        try {
            $query  = 'SELECT * FROM usuarios';
            $query = $this->Connectiondb->prepare($query);
            $query->execute();

            $DatoUsuarios = array();
            if ($query->rowCount()) {
                while ($row = $query->fetchAll(PDO::FETCH_ASSOC)) {
                    $DatoUsuarios = $row;
                }
            }
            echo json_encode($DatoUsuarios);
        } catch (Exception $e) {

            echo 'error: ' . $e;
        }
    }
    function Borrar($Id)
    {
        try {
            $sql = 'DELETE From usuarios WHERE id = :id';
            $query = $this->Connectiondb->prepare($sql);
            $query->bindParam(':id', $Id);
            $query->execute();

            echo "Se ha borrado con exito.";
        } catch (Exception $e) {

            //Reconocer un error y revertir los cambios
            $this->Connectiondb->rollBack();
            echo 'error: ' . $e;
        }
    }
}
