<?php

include "../../config/Database.php";
include "../../classes/Bodega.php";

$conn = new ConexionData();
$bodega = new Bodega($conn);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header('Content-Type: application/json');
    $bodega->amountHeld();
}
