<?php
session_start();
class Auth
{
    protected $Connection;
    protected $Connectiondb;

    function __construct()
    {
        $this->Connection = new Connection();
        $this->Connectiondb = $this->Connection->connect();
    }
    function Login($Documento, $Pass)
    {
        try {
            $this->Connectiondb->beginTransaction();

            $UserExist = ("SELECT * FROM usuarios WHERE documento = :Documento");
            $sql = $this->Connectiondb->prepare($UserExist);

            $passwordHash = md5($Pass);

            $sql->bindParam(':Documento', $Documento);
            $sql->execute();

            $result = $sql->fetch(PDO::FETCH_ASSOC);
            $Icon = "error";
            $_SESSION['Auth'] = "";

            if (($passwordHash === $result['pass'])) {
                $_SESSION['Auth'] =  $Documento;
                $Icon = "success";
                $Messege = "Se inició sesion correctamente.";
            } else {
                $Messege = "el usuario o contraseña incorrecta.";
            }
            $response = array(
                'r1' => $Messege,
                'r2' => $Icon,
                'CheckUser' => $_SESSION['Auth']
            );
            echo json_encode($response);
        } catch (PDOException $e) {
            echo 'The following error was presented: ' . $e;
        }
    }
    function LogOut($User)
    {
        try {
            if ($User == $_SESSION['Auth']) {
                session_destroy();
            }
        } catch (PDOException $e) {
            echo 'The following error was presented: ' . $e;
        }
    }
    function Check($ChecckUser)
    {
        try {
            if (!isset($_SESSION['Auth'])) {
                $response = array('status' => 'redirect', 'location' => '../index.html');
            } else {
                if ($ChecckUser == $_SESSION['Auth']) {
                    $response = array('status' => 'redirect', 'location' => 'home.html');
                } else {
                    $response = array('status' => 'error', 'message' => 'Invalid user');
                }
            }
            echo json_encode($response);
            exit();
        } catch (PDOException $e) {
            echo 'The following error was presented: ' . $e;
        }
    }
}
