<?php
include "../../config/Database.php";
include "../../classes/Consumible.php";

$conn = new ConexionData();
$consumible = new Consumible($conn);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $case = $_GET['case'];

    switch ($case) {
        case 'allConsumables':
            $consumible->showAll();
            break;
        case 'allConsumablesList':
            $data = $consumible->showListConsumable();
            echo json_encode($data);
            break;

        default:
            # code...
            break;
    }
}
