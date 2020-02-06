<?php
include "../../config/Database.php";
include "../../classes/Consumible.php";
include "../../classes/Bodega.php";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $conn = new ConexionData();

    new Bodega($conn);
    $consumible = new Consumible($conn);

    $cantidad = (int) $_POST['cantidad'];
    $marca = strtoupper($_POST['marca']);
    $modelo = strtoupper($_POST['modelo']);
    //$tipo = ucwords(strtolower($_POST['tipo']));
    $tipo = $_POST['tipo'];
    $bodega = (int) $_POST['bodega'];
    $impresora = strtoupper($_POST['impresora']);

    header('Content-Type: application/json');


    $consumible->addPrinterConsumables($cantidad, $marca, $tipo, $modelo, $bodega, $impresora);
}
