<?php

class Consumible
{
    public $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /* Insertar nuevo  */
    public function addPrinterConsumables(int $cantidad, string $marca, string $tipo, string $modelo, int $bodega, string $impresora)
    {
        $conn = $this->conn->connect();
        $sql = "INSERT INTO Consumible (Marca, Modelo, Tipo, Modelo_impresora) VALUES ('$marca', '$modelo', '$tipo', '$impresora')";

        $conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

        for ($i = 0; $i < $cantidad; $i++) {

            if ($conn->query($sql)) {
                $last_idConsumible = $conn->insert_id;
                $conn->query("INSERT INTO Bodega_Consumible VALUES (NULL, $bodega, $last_idConsumible)");
            } else {
                // echo "Error: " . $sql . "<br>" . $conn->error;
                $arreglo = array('status' => 'BAD');
                echo json_encode($arreglo);
            }
        }
        $conn->commit();
        $conn->close();
    }

    /* Mostrar todo lo de Manuel Orella */

    public function showAllMO()
    {
        $conn = $this->conn->connect();

        $sql = "SELECT C.Id_consumible, C.Modelo, C.Marca, C.Tipo, C.Modelo_impresora AS Impresora, COUNT(C.Modelo) AS Cantidad, B.Lugar FROM Consumible C INNER JOIN Bodega_Consumible BC ON C.Id_consumible=BC.Id_consumible INNER JOIN Bodega B ON BC.Id_bodega=B.Id_bodega AND B.Lugar='Manuel Orella' GROUP BY C.Modelo";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($data = mysqli_fetch_assoc($result)) {
                $arreglo["data"][] = array_map("utf8_encode", $data);
            }
            echo json_encode($arreglo);
        } else {
            $arreglo["data"][] = ["Id_consumible" => "", "Fecha" => "", "Marca" => "", "Modelo" => "", "Lugar" => "", "Tipo" => "", "Id_bodega" => "", "Impresora" => "", "Cantidad" => ""];
            echo json_encode($arreglo);
            //die("Error");
        }
        mysqli_free_result($result);
        $conn->close();
    }

    /**Mostrar todo de informaticaa */
    public function showAllINF()
    {
        $conn = $this->conn->connect();

        $sql = "SELECT C.Id_consumible, C.Modelo, C.Marca, C.Tipo, C.Modelo_impresora AS Impresora, COUNT(C.Modelo) AS Cantidad, B.Lugar FROM Consumible C INNER JOIN Bodega_Consumible BC ON C.Id_consumible=BC.Id_consumible INNER JOIN Bodega B ON BC.Id_bodega=B.Id_bodega AND B.Lugar='Informatica' GROUP BY C.Modelo";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($data = mysqli_fetch_assoc($result)) {
                $arreglo["data"][] = array_map("utf8_encode", $data);
            }
            echo json_encode($arreglo);
        } else {
            $arreglo["data"][] = ["Id_consumible" => "", "Fecha" => "", "Marca" => "", "Modelo" => "", "Lugar" => "", "Tipo" => "", "Id_bodega" => "", "Impresora" => "", "Cantidad" => ""];
            echo json_encode($arreglo);
            //die("Error");
        }
        mysqli_free_result($result);
        $conn->close();
    }

    public function showAll()
    {
        $conn = $this->conn->connect();

        $sql = "SELECT C.Id_consumible, C.Modelo, C.Marca, C.Tipo, C.Modelo_impresora AS Impresora, COUNT(C.Modelo) AS Cantidad FROM Consumible C INNER JOIN Bodega_Consumible BC ON C.Id_consumible=BC.Id_consumible INNER JOIN Bodega B ON BC.Id_bodega=B.Id_bodega GROUP BY C.Modelo";

        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($data = mysqli_fetch_assoc($result)) {
                $arreglo["data"][] = array_map("utf8_encode", $data);
            }
            echo json_encode($arreglo);
        } else {
            $arreglo["data"][] = ["Id_consumible" => "", "Fecha" => "", "Marca" => "", "Modelo" => "", "Lugar" => "", "Tipo" => "", "Id_bodega" => "", "Impresora" => "", "Cantidad" => ""];
            echo json_encode($arreglo);
            //die("Error");
        }
        mysqli_free_result($result);
        $conn->close();
    }


    /* Mostrar algunos segun la marca, modelo o bodega*/
    public function showSome($array)
    {
        $element = $array;

        $conn = $this->conn->connect();
        $condicion = "";

        if (!empty($element['bodega'])) {
            $bodega = $element['bodega'];
            $condicion .= "T.Id_bodega = $bodega";
        }

        if (!empty($element['marca'])) {
            if ($condicion != "") {
                $condicion .= " AND ";
            }
            $marca = $element['marca'];
            $condicion .= "T.Marca='$marca'";
        }

        if (!empty($element['modelo'])) {
            if ($condicion != "") {
                $condicion .= " AND ";
            }
            $modelo = $element['modelo'];
            $condicion .= "T.Modelo='$modelo'";
        }

        $sql = "SELECT T.Id_consumible, T.Marca, T.Modelo, T.Tipo, T.Codigo_barra, T.Modelo_impresora, T.Id_bodega, B.Lugar FROM Consumible T INNER JOIN Bodega B WHERE $condicion AND T.Id_bodega=B.Id_bodega";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($data = mysqli_fetch_assoc($result)) {
                $arreglo["data"][] = array_map("utf8_encode", $data);
            }
            echo json_encode($arreglo);
        } else {
            $arreglo["data"][] = ["Id_consumible" => "", "Marca" => "", "Modelo" => "", "Tipo" => "", "Codigo_barra" => "", "Modelo_impresora" => "", "Id_bodega" => "", "Lugar" => ""];
            //  die("Error");
            echo json_encode($arreglo);
        }
        mysqli_free_result($result);
        $conn->close();
    }


    public function deleteCon(int $cantidad, string $modelo, string $marca, string $tipo)
    {
        $conn = $this->conn->connect();
        /* Si es distinto a cero solo se borrarán algunos*/
        if ($cantidad != 0) {
            /* Comprobar la cantidad de consumibles en informatica */
            $sqlquery = "SELECT COUNT(C.Modelo) AS Cantidad FROM Consumible C INNER JOIN Bodega_Consumible BC ON C.Id_consumible=BC.Id_consumible INNER JOIN Bodega B ON BC.Id_bodega=B.Id_bodega AND B.Lugar = 'Informatica' AND C.Marca='$marca' AND C.Modelo='$modelo' AND C.Tipo='$tipo'";

            $result = $conn->query($sqlquery);

            if ($result->num_rows > 0) {
                while ($data = mysqli_fetch_assoc($result)) {
                    $arreglo = array_map("utf8_encode", $data);
                }

                if ($cantidad <=  $arreglo['Cantidad'] && $arreglo['Cantidad'] != 0) {
                    self::deleteConsumables($cantidad, $marca, $tipo, $modelo, 'Informatica');
                }
                /* Si la cantidad a eliminar es menor a la que existe en informatica */
                if ($cantidad > $arreglo['Cantidad']) {
                    $resto = $cantidad - $arreglo['Cantidad'];
                    self::deleteConsumables($arreglo['Cantidad'], $marca, $tipo, $modelo, 'Informatica');
                    self::deleteConsumables($resto, $marca, $tipo, $modelo, 'Manuel Orella');
                    $status = array("status" => "ok");
                    echo json_encode($status);
                }
            }

            mysqli_free_result($result);
            $conn->close();

            /* Si es igual a 0 se borraran todos los modelos de todas las bodegas*/
        } else {
            self::deleteAll($marca, $tipo, $modelo);
        }
    }

    /* Borra elementos consumibles segun cantidad*/
    public function deleteConsumables($cantidad, $marca, $tipo, $modelo, $bodega)
    {
        $conn = $this->conn->connect();

        $sqlquery = "SELECT C.Id_consumible FROM Consumible C INNER JOIN Bodega_Consumible BC ON C.Id_consumible=BC.Id_consumible INNER JOIN Bodega B ON B.Lugar = '$bodega' AND C.Marca='$marca' AND C.Modelo='$modelo' AND C.Tipo='$tipo' LIMIT $cantidad";

        $conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

        $result = $conn->query($sqlquery);
        if ($result->num_rows > 0) {
            while ($data = mysqli_fetch_assoc($result)) {
                $arreglo['id'][] = array_map("utf8_encode", $data);
            }
            foreach ($arreglo['id'] as $value) {
                foreach ($value as $id) {
                    $conn->query("DELETE FROM Consumible WHERE Id_consumible=$id");
                }
            }
            $status = array("status" => "ok");
            echo json_encode($status);
        }
        mysqli_free_result($result);
        $conn->commit();
        $conn->close();
    }


    /* Borra todos los modelos de las bodegas */
    private function deleteAll(string $marca, string $tipo, string $modelo)
    {
        $conn = $this->conn->connect();

        $sql = "DELETE FROM Consumible WHERE Modelo='$modelo' AND Marca='$marca' AND Tipo='$tipo'";

        $conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

        if ($conn->query($sql)) {
            $arreglo = array("status" => "ok");
            echo json_encode($arreglo);
        } else {
            //echo "Error deleting record: " . $conn->error;
            $arreglo = array("status" => "bad");
            echo json_encode($arreglo);
        }

        $conn->commit();
        $conn->close();
    }

    public function transfer(int $cantidad, string $marca, string $modelo, string $tipo, string $origen, string $destino)
    {
        $conn = $this->conn->connect();

        if ($destino == "Manuel Orella") {
            $codDestino = 1;
        }
        if ($destino == "Informatica") {
            $codDestino = 2;
        }

        if ($cantidad != 0) {
            /* Comprobar la cantidad de consumibles en informatica */
            $sqlquery = "SELECT COUNT(C.Modelo) AS Cantidad FROM Consumible C INNER JOIN Bodega_Consumible BC ON C.Id_consumible=BC.Id_consumible INNER JOIN Bodega B ON BC.Id_bodega=B.Id_bodega AND B.Lugar = '$origen' AND C.Marca='$marca' AND C.Modelo='$modelo' AND C.Tipo='$tipo'";

            $result = $conn->query($sqlquery);

            if ($result->num_rows > 0) {
                while ($data = mysqli_fetch_assoc($result)) {
                    $arreglo = array_map("utf8_encode", $data);
                }

                if ($cantidad <=  $arreglo['Cantidad']) {

                    self::transferUpdate($cantidad,  $marca,  $tipo,  $modelo,  $origen,  $codDestino);
                    $status = array("status" => "ok");
                    echo json_encode($status);
                }
            }

            mysqli_free_result($result);


            /* Si es igual a 0 se moveran todos los modelos de todas la bodega*/
        } else {
            $sqlquery = "SELECT C.Id_consumible FROM Consumible C INNER JOIN Bodega_Consumible BC ON C.Id_consumible=BC.Id_consumible INNER JOIN Bodega B ON BC.Id_bodega=B.Id_bodega AND B.Lugar = '$origen' AND C.Marca='$marca' AND C.Modelo='$modelo' AND C.Tipo='$tipo'";

            $conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

            $result = $conn->query($sqlquery);
            if ($result->num_rows > 0) {
                while ($data = mysqli_fetch_assoc($result)) {
                    $arreglo['id'][] = array_map("utf8_encode", $data);
                }
                foreach ($arreglo['id'] as $value) {
                    foreach ($value as $id) {
                        $conn->query("UPDATE `Bodega_Consumible` SET `Id_bodega`=$codDestino WHERE Id_consumible=$id");
                    }
                }
            }
            mysqli_free_result($result);
            $conn->commit();

            $status = array("status" => "ok");
            echo json_encode($status);
        }
        $conn->close();
    }

    private function transferUpdate(int $cantidad, string $marca, string $tipo, string $modelo, string $bodega, int $codDestino)
    {

        $conn = $this->conn->connect();

        $sqlquery = "SELECT C.Id_consumible FROM Consumible C INNER JOIN Bodega_Consumible BC ON C.Id_consumible=BC.Id_consumible INNER JOIN Bodega B ON BC.Id_bodega=B.Id_bodega AND B.Lugar = '$bodega' AND C.Marca='$marca' AND C.Modelo='$modelo' AND C.Tipo='$tipo' LIMIT $cantidad";

        $conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

        $result = $conn->query($sqlquery);
        if ($result->num_rows > 0) {
            while ($data = mysqli_fetch_assoc($result)) {
                $arreglo['id'][] = array_map("utf8_encode", $data);
            }
            foreach ($arreglo['id'] as $value) {
                foreach ($value as $id) {
                    $conn->query("UPDATE `Bodega_Consumible` SET `Id_bodega`=$codDestino WHERE Id_consumible=$id");
                }
            }
        }
        mysqli_free_result($result);
        $conn->commit();
        $conn->close();
    }

    /*  Actualizar  */
    public function update(string $marca_new, string $modelo_new, string $tipo_new, string $impresora_new, string $marca_old, string $modelo_old, string $tipo_old, string $impresora_old)
    {
        $conn = $this->conn->connect();

        $sql = "UPDATE Consumible SET Marca='$marca_new', Modelo='$modelo_new', Tipo='$tipo_new', Modelo_impresora='$impresora_new' WHERE Marca='$marca_old' AND Modelo='$modelo_old' AND Tipo='$tipo_old' AND Modelo_impresora='$impresora_old'";

        $arreglo = array("status" => "ok");

        if ($conn->query($sql)) {
            echo json_encode($arreglo);
        } else {
            $arreglo = array("status" => "bad");
            echo json_encode($arreglo);
            //echo "Error updating record: " . $conn->error;
        }

        $conn->close();
    }
}
