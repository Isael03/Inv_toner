<?php
include "../../config/Database.php";
include "../../classes/Consumible.php";
include "../../classes/Bodega.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $conn = new ConexionData();
    //new Bodega($conn);
    $consumible = new Consumible($conn);
    header('Content-Type: application/json');

    $case = $_POST['case'];

    switch ($case) {
        case 'addPrinterConsumables':
            $cantidad = (int) $_POST['cantidad'];
            $marca = strtoupper($_POST['marca']);
            $modelo = strtoupper($_POST['modelo']);
            //$tipo = ucwords(strtolower($_POST['tipo']));
            $tipo = $_POST['tipo'];
            $bodega = (int) $_POST['bodega'];
            //$impresora = strtoupper($_POST['impresora']);
            $impresora = (int) $_POST['impresora'];
            $rangoMinimo = (int) $_POST['rangoMinimo'];
            $rangoMaximo = (int) $_POST['rangoMaximo'];

            $res = $consumible->addPrinterConsumables($cantidad, $marca, $tipo, $modelo, $bodega, $impresora, $rangoMinimo, $rangoMaximo);
            ($res) ? $status = array("status" => "ok") : $status = array("status" => "bad");
            echo json_encode($status);
            break;

        case 'addConsumablesExists':
            $cantidad = (int) $_POST['cantidad'];
            $id_consumible = (int) $_POST['Id_consumible'];
            $Id_bodega = (int) $_POST['Id_bodega'];

            $res = $consumible->addConsumablesExists($cantidad, $id_consumible, $Id_bodega);
            ($res) ? $status = array("status" => "ok") : $status = array("status" => "bad");
            echo json_encode($status);

            break;

        default:
            # code...
            break;
    }
}
