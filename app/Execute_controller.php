<?php
include  'auth/Auth.php';
include  'Connection.php';

$Auth = new Auth();

//Login
if (isset($_POST['passLogin']) && isset($_POST['cedulaLogin'])) {
    $Auth->Login($_POST['cedulaLogin'], $_POST['passLogin']);
}

//LogOut
if (isset($_POST["LogOut"])) {
    $Auth->LogOut($_POST["LogOut"]);
}
