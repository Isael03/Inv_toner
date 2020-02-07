<?php
include "../../config/Database.php";
include "../../classes/Consumible.php";

$conn = new ConexionData();
$consumible = new Consumible($conn);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $consumible->showAllINF();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $arreglo = $_POST;

    if (!empty($arreglo)) {
        $consumible->showSome($arreglo);
    }
}
