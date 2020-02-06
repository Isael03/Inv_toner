<?php

include "../../config/Database.php";
include "../../classes/Retiro.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $db = new ConexionData();
    $retiro = new Retiro($db);

    //$id_dir=$_GET['id'];

    $retiro->getDepart(1, "2020-01-01", "2020-01-30");
    // echo json_encode($data);
}
