<?php
include "../../config/Database.php";
include "../../classes/Consumible.php";

$conn = new ConexionData();
$consumible = new Consumible($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $cantidad = (int) $_POST['cantidad'];
    $origen = (int) $_POST['origen'];
    $destino = (int) $_POST['destino'];
    $Id_consumible = (int) $_POST['Id_consumible'];


    $res = $consumible->transfer($cantidad, $origen, $destino, $Id_consumible);

    ($res) ?  $status = array("status" => "ok") :   $status = array("status" => "bad");
    echo json_encode($status);
}
