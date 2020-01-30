<?php

include "../../config/Database.php";
include "../../classes/Retiro.php";


$db = new ConexionData();
$retiro = new Retiro($db);


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $retiro->showAll();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mes = (int) $_POST['mes'];

    $retiro->filterByMonth($mes);
}
