<?php

include "../../config/Database.php";
include "../../classes/Retiro.php";


$db = new ConexionData();
$retiro = new Retiro($db);


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $retiro->showAll();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $caso = (int) $_POST['case'];

    switch ($caso) {
        case 2:
            $inicio = $_POST['inicio'];
            $termino = $_POST['termino'];
            $retiro->filterRangeHistorial($inicio,  $termino);
            break;

        default:
            # code...
            break;
    }
}
