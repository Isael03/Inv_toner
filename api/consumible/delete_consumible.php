<?php
include "../../config/Database.php";
include "../../classes/Consumible.php";

$conn = new ConexionData();
$consumible = new Consumible($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $cantidad = (int) $_POST['cantidad'];
    $modelo = strtoupper($_POST['modelo']);
    $marca = strtoupper($_POST['marca']);
    $tipo = $_POST['tipo'];
    $id_bodega = (int) $_POST['id_bodega'];

    $res = $consumible->deleteCon($cantidad, $modelo, $marca, $tipo, $id_bodega);
    ($res) ?  $status = array("status" => "ok") :   $status = array("status" => "bad");
    echo json_encode($status);
}
