<?php

include "../../config/Database.php";
include "../../classes/Consumible.php";
include_once "../../classes/Impresora.php";

$conn = new ConexionData();
$consumible = new Consumible($conn);
$impresora = new Impresora($conn);

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

    /*  echo  "nuevos: " . $marca_new;
    echo  $modelo_new;
    echo  $tipo_new;
    echo  $impresora_new;
    echo  "viejos: " . $marca_old;
    echo  $modelo_old;
    echo  $tipo_old;
    echo  $impresora_old;
 */
    $idImpresora_old = $impresora->showId($impresora_old);

    $idImpresora_new = $impresora->showId($impresora_new);

    /* echo "id impresora viejo: " . $idImpresora_old;
    echo " id impresora nueva: " . $idImpresora_new; */

    if (isset($idImpresora_new) && isset($idImpresora_old)) {

        $valid = $consumible->update($marca_new, $modelo_new, $tipo_new, $idImpresora_new, $marca_old, $modelo_old, $tipo_old, $idImpresora_old);

        if ($valid) {
            echo json_encode(array("status" => "ok"));
        } else {
            echo json_encode(array("status" => "bad"));
        }
    }
}
