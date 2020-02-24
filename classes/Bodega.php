<?php

class Bodega
{

    public $conn;

    public function __construct($conn)
    {

        $this->conn = $conn;
        /**Al inicializarse se crean 2 bodegas */
        self::checkStorage($conn);
    }

    /**Crear 2 bodegas  */
    private function checkStorage($conn)
    {
        $conn = $conn->connect();
        $sql = "SELECT * FROM Bodega";

        $result = $conn->query($sql);

        if ($result->num_rows === 0) {
            $sql = "INSERT INTO Bodega (Id_bodega, Lugar) VALUES (1, 'Bodega A'), (2, 'Bodega B')";
            if ($conn->query($sql)) {
                //echo "Bodegas ingresadas";
            };
        }
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
            $arreglo['data'] = array();
        }

        mysqli_free_result($result);
        $conn->close();

        if (isset($arreglo['data'])) {
            return $arreglo;
        }
    }

    /**Añadir nueva bodega */
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

    /**Modificar nombre de la bodega*/
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

    /**Eliminar bodega */
    public function deleteStorage(int $id)
    {

        /* if ($id < 3) { */
        $conn = $this->conn->connect();

        $sql = "DELETE FROM Bodega WHERE Id_bodega=$id";

        if ($conn->query($sql)) {
            $valid = true;
        } else {
            //echo $conn->error;
            $valid = false;
        }
        $conn->close();
        /* }  else {
            $valid = false;
        } */
        return $valid;
    }

    public function checkStorageExistence(int $Id_bodega, string $nombre)
    {
        $conn = $this->conn->connect();

        $sql = "SELECT * FROM Bodega WHERE Id_bodega=$Id_bodega";
        $result = $conn->query($sql);

        if ($result->num_rows > 2) {
            $res = $Id_bodega;
        } else {
            $sqlquery = "SELECT Id_bodega from Bodega WHERE Lugar='$nombre'";
            $result = $conn->query($sqlquery);
            if ($result->num_rows > 0) {
                while ($data = $result->fetch_assoc()) {
                    $arreglo = array_map('utf8_encode', $data);
                }
                $res = $arreglo['Id_bodega'];
            } else {
                $res = "";
            }
        }
        mysqli_free_result($result);
        $conn->close();
        return $res;
    }
}
