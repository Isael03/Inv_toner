<?php

include "../../config/Database.php";
include "../../classes/Retiro.php";
include "../../classes/Consumible.php";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $db = new ConexionData();
    $consumible = new Consumible($db);
    $retiro = new Retiro($db);
    $retiro->consumible = $consumible;

    $marca = strtoupper($_POST['marca']);
    $usuarioRetira = strtoupper($_POST['usuarioRetira']);
    $usuarioRecibe = strtoupper($_POST['usuarioRecibe']);
    //$departamento = strtoupper($_POST['departamento']);
    $departamento = strtoupper("Todavia falta");
    $modelo = strtoupper($_POST['modelo']);
    //$tipo = ucfirst(strtolower($_POST['tipo']));
    $tipo = $_POST['tipo'];
    $codigo = strtoupper($_POST['codigo']);
    //$bodega = ucwords(strtolower($_POST['bodega']));
    $bodega = $_POST['bodega'];
    $id = (int) $_POST['id'];

    $retiro->insertWithdraw($usuarioRetira, $usuarioRecibe, $departamento, $marca, $modelo, $tipo, $codigo, $bodega, $id);
}
