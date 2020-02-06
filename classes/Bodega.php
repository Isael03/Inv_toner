<?php

class Bodega
{

    public $conn;

    public function __construct($conn)
    {

        $this->conn = $conn;
        self::checkStorage($conn);
    }


    private function checkStorage($conn)
    {
        $conn = $conn->connect();
        $sql = "SELECT * FROM Bodega";

        $result = $conn->query($sql);

        if ($result->num_rows === 0) {
            $sql = "INSERT INTO Bodega (Id_bodega, Lugar) VALUES (1, 'Manuel Orella'), (2, 'Informatica')";
            if ($conn->query($sql)) {
                //echo "Bodegas ingresadas";
            };
        }
        $conn->close();
    }

    public function amountHeld()
    {

        $conn = $this->conn->connect();

        $sql = "SELECT COUNT(C.Id_consumible) AS Cantidad_MO FROM Consumible C INNER JOIN Bodega_Consumible BC ON C.Id_consumible=BC.Id_consumible INNER JOIN Bodega B ON BC.Id_bodega=B.Id_bodega AND B.Lugar='Manuel Orella'";


        $sql2 = "SELECT COUNT(C.Id_consumible) AS Cantidad_INF FROM Consumible C INNER JOIN Bodega_Consumible BC ON C.Id_consumible=BC.Id_consumible INNER JOIN Bodega B ON BC.Id_bodega=B.Id_bodega AND B.Lugar='Informatica'";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($data = mysqli_fetch_assoc($result)) {
                $arreglo['MO'] = array_map("utf8_encode", $data);
            }
        } else {
            die("Error");
        }

        $result = $conn->query($sql2);

        if ($result->num_rows > 0) {
            while ($data = mysqli_fetch_assoc($result)) {
                $arreglo['INF'] = array_map("utf8_encode", $data);
            }
        } else {
            die("Error");
        }


        echo json_encode($arreglo);


        mysqli_free_result($result);
        $conn->close();
    }
}
