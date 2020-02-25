<?php

include "../../config/Database.php";
include "../../classes/Bodega.php";

$conn = new ConexionData();
$bodega = new Bodega($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $case = $_POST['case'];

    switch ($case) {
            /**Insertar nueva bodega */
        case 'addStorage':

            $nameStorage = ucwords(strtolower($_POST['nombreBodega']));

            $res = $bodega->addStorage($nameStorage);
            echo json_encode(array('status' => $res));
            break;

            /**Modificar nombre de la bodega */
        case 'updateStorage':

            $newName = ucwords(strtolower($_POST['nuevoNombre']));
            $id = (int) $_POST['id'];

            $res = $bodega->updateStorage($id, $newName);
            echo json_encode(array('status' => $res));
            break;

            /**Eliminar bodega */
        case 'deleteStorage':

            $id = (int) $_POST['id'];

            $res = $bodega->deleteStorage($id);
            echo json_encode(array('status' => $res));
            break;


        default:
            break;
    }
}
