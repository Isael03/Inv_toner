<?php

include_once "../../classes/Funcionario.php";
include_once "../../config/Database.php";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $db = new ConexionData();

    $nombre = $_GET['nombre'];

    $funcionario = new Funcionario($db);

    $funcionario->search($nombre);
}
