<?php

include_once "../../classes/Impresora.php";
include_once "../../config/Database.php";

$db = new ConexionData();
$impresora = new Impresora($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $case = $_POST['case'];

    switch ($case) {

            /* Ingresar nueva impresora */
        case 'newPrinter':
            $marca = strtoupper($_POST['marca']);
            $modelo = strtoupper($_POST['modelo']);

            $valid = $impresora->insertPrinter($marca, $modelo);

            if ($valid) {
                echo json_encode(array("status" => "ok"));
            } else {
                echo json_encode(array("status" => "bad"));
            }
            break;

            /* Actualizar impresora */
        case 'updatePrinter':
            $marca = strtoupper($_POST['marca']);
            $modelo = strtoupper($_POST['modelo']);
            $id = (int) $_POST['id'];

            $valid = $impresora->updatePrinter($marca, $modelo, $id);

            if ($valid) {
                echo json_encode(array("status" => "ok"));
            } else {
                echo json_encode(array("status" => "bad"));
            }
            break;

        case 'deletePrinter':
            $id = (int) $_POST['id'];
            $valid = $impresora->deletePrinter($id);

            if ($valid) {
                echo json_encode(array("status" => "ok"));
            } else {
                echo json_encode(array("status" => "bad"));
            }
            break;

        default:
            # code...
            break;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $case = $_GET['case'];
    switch ($case) {


        case 'showPrinters':
            $data = $impresora->showPrinters();

            if (isset($data)) {
                echo json_encode($data);
            } else {
                $arreglo['data'][] = array('Marca_impresora' => "", "Modelo_impresora" => "");
                echo json_encode($arreglo);
            }
            break;


        case 'printersBrand':
            $data = $impresora->printersBrand();
            echo json_encode($data);

            break;

        case 'showModelPrinter':

            $marca = strtoupper($_GET['marca']);
            $data = $impresora->showModelPrinter($marca);
            echo json_encode($data);

            break;

        default:
            # code...
            break;
    }
}
