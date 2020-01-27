<?php

include "../../config/Database.php";
include "../../classes/Consumible.php";

$conn = new ConexionData();
$consumible = new Consumible($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // header('Content-Type: application/json');

    $marca_new = strtoupper($_POST['marca_new']);
    $modelo_new = strtoupper($_POST['modelo_new']);
    $tipo_new = $_POST['tipo_new'];
    $impresora_new = strtoupper($_POST['impresora_new']);

    $marca_old = strtoupper($_POST['marca_old']);
    $modelo_old = strtoupper($_POST['modelo_old']);
    $tipo_old = $_POST['tipo_old'];
    $impresora_old = strtoupper($_POST['impresora_old']);

    $modelo_new = str_replace(" ", "-", $modelo_new);

    /* echo  "nuevos: " . $marca_new;
    echo  $modelo_new;
    echo  $tipo_new;
    echo  $impresora_new;
    echo  "viejos: " . $marca_old;
    echo  $modelo_old;
    echo  $tipo_old;
    echo  $impresora_old; */



    $consumible->update($marca_new, $modelo_new, $tipo_new, $impresora_new, $marca_old, $modelo_old, $tipo_old, $impresora_old);
}
