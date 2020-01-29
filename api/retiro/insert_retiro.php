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
    $usuarioRetira = $_POST['usuarioRetira'];
    $usuarioRecibe = $_POST['usuarioRecibe'];
    $departamento = strtoupper("Todavia falta");
    $modelo = strtoupper($_POST['modelo']);
    $tipo = $_POST['tipo'];
    $cantidad = (int) $_POST['cantidad'];
    $impresora = strtoupper($_POST['impresora']);


    $retiro->insertWithdraw($cantidad, $usuarioRetira, $usuarioRecibe, $departamento, $marca, $modelo, $tipo, $impresora);
}
