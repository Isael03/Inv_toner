<?php
include "../../config/Database.php";
include "../../classes/Consumible.php";

$conn = new ConexionData();
$consumible = new Consumible($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $cantidad = (int) $_POST['cantidad'];
    $modelo = strtoupper($_POST['modelo']);
    $marca = strtoupper($_POST['marca']);
    $tipo = $_POST['tipo'];
    $origen = $_POST['origen'];
    $destino = $_POST['destino'];


    $consumible->transfer($cantidad, $marca, $modelo, $tipo, $origen, $destino);
}
