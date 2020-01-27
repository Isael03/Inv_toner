<?php

include "../../config/Database.php";
include "../../classes/Retiro.php";



$db = new ConexionData();
$retiro = new Retiro($db);




$retiro->showAll();
