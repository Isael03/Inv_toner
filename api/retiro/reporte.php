<?php

include "../../config/Database.php";
include "../../classes/Retiro.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $db = new ConexionData();
    $retiro = new Retiro($db);

    $case = $_POST['case'];

    switch ($case) {
        case 'general':
            //echo "";
            $inicio = $_POST['inicio'];
            $termino = $_POST['termino'];

            $general_Report = $retiro->general_Report($inicio, $termino);

            if (isset($general_Report['depart']) && isset($general_Report['model'])) {
                echo json_encode($general_Report);
            } else {
                echo json_encode(array("status" => 'bad'));
            }
            break;

        case 'dep':

            $dep_Report = $retiro->getDir();

            if (isset($dep_Report)) {
                $res = array("status" => "ok", "data" => $dep_Report);
            } else {
                $res = array("status" => 'bad');
            }
            echo json_encode($res);
            break;


        default:
            # code...
            break;
    }
}
