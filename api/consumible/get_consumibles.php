<?php
include "../../config/Database.php";
include "../../classes/Consumible.php";

$conn = new ConexionData();
$consumible = new Consumible($conn);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $lugar = (int) $_GET['bodega'];
    $res = $consumible->getConsumiblesStorage($lugar);

    echo json_encode($res);
}
