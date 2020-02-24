<?php

include "../../config/Database.php";
include "../../classes/Retiro.php";
include "../../classes/Consumible.php";
include "../../classes/Impresora.php";
include "../../classes/Bodega.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_retiro = (int) $_POST['id_retiro'];

    $conn = new ConexionData();
    $consumible = new Consumible($conn);
    $retiro = new Retiro($conn);
    $impresora = new Impresora($conn);
    $bodega = new Bodega($conn);

    $data = $retiro->searchWithdraw($id_retiro); //Buscar datos

    if (isset($data)) {
        $id_impresora = (int) $data['Id_impresora'];
        $nombre_impresora = $data['Impresora'];
        $id_bodega = (int) $data['Id_bodega'];
        $nombre_bodega = $data['Bodega'];
        $cantidad = (int) $data['Cantidad'];
        $marca = $data['Marca'];
        $tipo = $data['Tipo'];
        $modelo = $data['Modelo'];

        //Comprobar que la impresora y la bodega aun existen
        $res_Id_impresora = $impresora->checkPrinterExistence($id_impresora, $nombre_impresora);
        $res_Id_bodega = $bodega->checkStorageExistence($id_bodega, $nombre_bodega);

        $conn = $conn->connect();
        $conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

        if ($res_Id_impresora !== "" && $res_Id_bodega !== "") {
            //insertar en la tabla consumible y borrar de la tabla retiro
            $resInsert = $consumible->addPrinterConsumables($cantidad, $marca, $tipo, $modelo, $res_Id_bodega, $res_Id_impresora);
            if ($resInsert) {
                if ($retiro->deleteWithdraw($id_retiro)) {
                    $res = true;
                } else {
                    $res = false;
                    $conn->rollback();
                }
            } else {
                $res = false;
                $conn->rollback();
            }
        } else {
            $res = false;
            $conn->rollback();
        }
        $conn->commit();
        $conn->close();
        ($res) ? $status = array("status" => "ok") : $status = array("status" => "bad");
        echo json_encode($status);
    } else {
        $status = array("status" => "not_exists");
        echo json_encode($status);
    }

}
