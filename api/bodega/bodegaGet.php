<?php

include "../../config/Database.php";
include "../../classes/Bodega.php";

$conn = new ConexionData();
$bodega = new Bodega($conn);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $case = $_GET['case'];

    switch ($case) {
        case 'listStorage':
            $nameStorage = $bodega->listStorage();
            echo json_encode($nameStorage);
            break;
        case 'amountHeld':
            $bodega->amountHeld();
            break;

        default:
            # code...
            break;
    }
}
