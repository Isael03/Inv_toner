<?php

include "../../config/Database.php";
include "../../classes/Retiro.php";
include "../../classes/Consumible.php";
include "../../classes/Funcionario.php";
include "../../classes/Impresora.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $db = new ConexionData();
    $consumible = new Consumible($db);

    $retiro = new Retiro($db);
    $retiro->consumible = $consumible;
    //$impresora = new Impresora($conn);
    $funcionario = new Funcionario($db);

    $marca = strtoupper($_POST['marca']);
    //$usuarioRetira = $_POST['usuarioRetira'];
    $usuarioRetira = 'Falta';
    $usuarioRecibe = $_POST['usuarioRecibe'];
    $modelo = strtoupper($_POST['modelo']);
    $tipo = $_POST['tipo'];
    $cantidad = (int) $_POST['cantidad'];
    $nombre_impresora = $_POST['impresora'];
    $id_impresora = (int) $_POST['id_impresora'];
    $bodega = (int) $_POST['bodega'];
    $nombreBodega = $_POST['nombreBodega'];
    $Id_consumible = (int) $_POST['Id_consumible'];

    $data = $funcionario->officialData($usuarioRecibe);

    //echo json_encode($data);

    $idRecibe = (int) $data['IdFuncionario'];
    $idDir = (int) $data['IdDireccion'];
    $idDepart = (int) $data['ID_departamento'];
    $nombreDepartamento = $data['Departamento'];

    //$id_impresora = (int) $impresora->showId($nombre_impresora);

    $res = $retiro->insertWithdrawINF_MO($cantidad, $usuarioRetira, $usuarioRecibe, $marca, $modelo, $tipo, strtoupper($nombre_impresora), $id_impresora, $bodega, $idDir, $idDepart, $idRecibe, $nombreDepartamento,  $nombreBodega, $Id_consumible);

    // echo json_encode($res);

    ($res) ?  $status = array("status" => "ok") :   $status = array("status" => "bad");
    echo json_encode($status);
}
