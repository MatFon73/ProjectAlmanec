<?php
class Mesa{
    protected $Connection;
    protected $Connectiondb;

    function __construct()
    {
        $this->Connection = new Connection();
        $this->Connectiondb = $this->Connection->connect();
    }

    function Añadir($id_usuario, $Departamento, $Municipio, $Zona, $Puesto, $N_mesa, $Lugar)
    {
        try {
            //Iniciar una transacción, desactivando autocommit
            $this->Connectiondb->beginTransaction();
            $query = 'INSERT INTO mesa (id_usuario, departamento, municipio , zona, puesto, n_mesa, lugar) VALUES (:id_usuario, :Departamento, :Municipio,:Zona, :Puesto, :N_mesa, :Lugar)';

            //Ejecuta una sentencia
            $sql = $this->Connectiondb->prepare($query);

            $sql->bindParam(':id_usuario', $id_usuario);
            $sql->bindParam(':Departamento', $Departamento);
            $sql->bindParam(':Municipio', $Municipio);
            $sql->bindParam(':Zona', $Zona);
            $sql->bindParam(':Puesto', $Puesto);
            $sql->bindParam(':N_mesa', $N_mesa);
            $sql->bindParam(':Lugar', $Lugar);

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