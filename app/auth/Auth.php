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

    function SignUp($Name, $User, $Pass)
    {
        try {
            $this->Connectiondb->beginTransaction();
            $UserExist = ("SELECT COUNT(*) FROM user WHERE user = :User");
            $sql = $this->Connectiondb->prepare($UserExist);
            $sql->bindParam(':User', $User);
            $sql->execute();

            if ($sql->fetchColumn() > 0) {
                $Icon = "error";
                $Messege = 'User already exists.';
            } else {
                $query = 'INSERT INTO user (name, user, pass) VALUES (:Name, :User, :Pass)';

                $sql = $this->Connectiondb->prepare($query);

                $sql->bindParam(':Name', $Name);
                $sql->bindParam(':User', $User);
                $passwordHash = md5($Pass);
                $sql->bindParam(':Pass', $passwordHash);
                $sql->execute();

                $this->Connectiondb->commit();

                mkdir("../uploads/" . $User, 0777, true);

                $Icon = "success";
                $Messege = "Successfully Registered.";
            }
            $response = array(
                'r1' => $Messege,
                'r2' => $Icon
            );
            echo json_encode($response);
        } catch (PDOException $e) {
            $this->Connectiondb->rollBack();
            echo 'The following error was presented: ' . $e;
        }
    }
    function Login($User, $Pass)
    {
        try {
            $this->Connectiondb->beginTransaction();

            $UserExist = ("SELECT * FROM user WHERE user = :User");
            $sql = $this->Connectiondb->prepare($UserExist);

            $passwordHash = md5($Pass);

            $sql->bindParam(':User', $User);
            $sql->execute();

            $result = $sql->fetch(PDO::FETCH_ASSOC);
            $Icon = "error";
            $_SESSION['Auth'] = "";

            if (($passwordHash === $result['pass'])) {
                $_SESSION['Auth'] =  $User;
                $Icon = "success";
                $Messege = "Successfully Login";
            } else {
                $Messege = "User or password Incorret.";
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
