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
            $sql = "INSERT INTO Bodega (Id_bodega, Lugar) VALUES (1, 'Informatica'), (2, 'Bodega A'), (3, 'Bodega B')";
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

    /**Listar nombres de bodega y la cantidad que tienen alamacenada*/
    public function listStorage()
    {


        $conn = $this->conn->connect();

        $sql = "SELECT B.Id_bodega, B.Lugar, if(COUNT(BC.Id_bodega)=0,0, COUNT(BC.Id_bodega)) AS Cantidad FROM Bodega B left JOIN Bodega_Consumible BC ON B.Id_bodega=BC.Id_bodega GROUP by B.Id_bodega ORDER BY B.Id_bodega ASC";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($data = mysqli_fetch_assoc($result)) {
                $arreglo['data'][] = array_map("utf8_encode", $data);
            }
        } else {
            //die("Error");
            $arreglo['data'][] = array("Id_bodega" => "", "Lugar" => "", "Cantidad" => "");
        }

        mysqli_free_result($result);
        $conn->close();

        if (isset($arreglo['data'])) {
            return  $arreglo;
        } /* else {
            return $arreglo['data'][] = array("Id_bodega" => "", "Lugar" => "", "Cantidad" => "");
        } */
    }

    public function addStorage(string $nameStorage)
    {
        $conn = $this->conn->connect();
        $sql = "INSERT INTO Bodega (Lugar) VALUES('$nameStorage')";

        if ($conn->query($sql)) {
            $valid = true;
        } else {
            //echo $conn->error;
            $valid = false;
        }
        $conn->close();
        return $valid;
    }

    public function updateStorage(int $id, string $nuevoNombre)
    {
        $conn = $this->conn->connect();

        $sql = "UPDATE Bodega SET Lugar='$nuevoNombre' WHERE Id_bodega=$id";

        if ($conn->query($sql)) {
            $valid = true;
        } else {
            //echo $conn->error;
            $valid = false;
        }
        $conn->close();
        return $valid;
    }

    public function deleteStorage(int $id)
    {

        if ($id > 3) {
            $conn = $this->conn->connect();

            $sql = "DELETE FROM Bodega WHERE Id_bodega=$id";

            if ($conn->query($sql)) {
                $valid = true;
            } else {
                //echo $conn->error;
                $valid = false;
            }
            $conn->close();
        } else {
            $valid = false;
        }
        return $valid;
    }
}
