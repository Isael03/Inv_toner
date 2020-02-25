<?php
include "../../config/Database.php";
include "../../classes/Consumible.php";

$conn = new ConexionData();
$consumible = new Consumible($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $case = $_POST['case'];
    switch ($case) {
            //Borrar cantidades de la tabla bodega_consumible
        case 'deleteCon':
            $cantidad = (int) $_POST['cantidad'];
            $id_bodega = (int) $_POST['id_bodega'];
            $Id_consumible = (int) $_POST['Id_consumible'];

            $res = $consumible->deleteCon($cantidad, $id_bodega, $Id_consumible);
            ($res) ?  $status = array("status" => "ok") :   $status = array("status" => "bad");
            header('Content-Type: application/json');
            echo json_encode($status);
            break;

            //Borrar consumible de la tabla consumible
        case 'deleteAll':
            $Id_consumible = (int) $_POST['Id_consumible'];
            $res = $consumible->deleteAll($Id_consumible);
            ($res) ?  $status = array("status" => "ok") :   $status = array("status" => "bad");
            header('Content-Type: application/json');
            echo json_encode($status);
            break;

        default:
            # code...
            break;
    }
}
