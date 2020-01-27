<?php

class ConexionData
{

    private $servername = "localhost";
    private $username = "root";
    private $password = "root";
    private $dbname = "Consumibles_impresoras_v2";



    public function connect()
    {
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        //echo "Connected successfully";
        return $conn;
    }
}
/* 
$db = new ConexionData();

$conn = $db->connect(); */
