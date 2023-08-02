<?php
class Empleados
{
    protected $Connection;
    protected $Connectiondb;

    function __construct()
    {
        $this->Connection = new Connection();
        $this->Connectiondb = $this->Connection->connect();
    }
    function Mostrar()
    {
        try {
            $query  = 'SELECT * FROM mesa';
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
            $sql = 'DELETE From mesa WHERE id = :id';
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
