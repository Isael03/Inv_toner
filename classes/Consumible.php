<?php

class Consumible
{
    public $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /* Insertar nuevos Consumibles  */
    public function addPrinterConsumables(int $cantidad, string $marca, string $tipo, string $modelo, int $bodega, string $impresora)
    {
        $conn = $this->conn->connect();

        $query = "SELECT Id_impresora from Impresora WHERE Modelo_impresora='$impresora' AND Marca_impresora='$marca'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            while ($data = $result->fetch_assoc()) {
                $arreglo = array_map('utf8_encode', $data);
            }
        } else {
            $valid = false;
        }
        $Id_impresora = (int) $arreglo['Id_impresora'];

        $sql = "INSERT INTO Consumible (Marca, Modelo, Tipo, Id_impresora) VALUES ('$marca', '$modelo', '$tipo', '$Id_impresora')";

        $conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

        $valid = true;

        for ($i = 0; $i < $cantidad; $i++) {

            if ($conn->query($sql)) {

                $last_idConsumible = $conn->insert_id;

                $sql2 = "INSERT INTO Bodega_Consumible (Id_bodega, Id_consumible) VALUES ($bodega, $last_idConsumible)";
                if ($conn->query($sql2) === false) {
                    $valid = false;
                    $conn->rollback();
                    //echo "Error: " . $sql . "<br>" . $conn->error;
                    break;
                }
            } else {
                //echo "Error: " . $sql . "<br>" . $conn->error;
                $conn->rollback();
                $valid = false;
                break;
            }
        }

        /* Enviar respuesta del proceso */
        if ($valid) {
            $arreglo = array('status' => 'ok');
            echo json_encode($arreglo);
        } else {
            $arreglo = array('status' => 'bad');
            echo json_encode($arreglo);
        }

        $conn->commit();
        $conn->close();
    }

    /**Obtener el contenido de cada bodega*/
    public function getConsumiblesStorage(int $lugar = null)
    {
        $conn = $this->conn->connect();

        $sql = "SELECT C.Id_consumible, C.Modelo, C.Marca, C.Tipo, CONCAT(I.Marca_impresora, ' ', I.Modelo_impresora) AS Impresora, COUNT(C.Modelo) AS Cantidad, B.Lugar, BC.Id_bodega  FROM Consumible C INNER JOIN Bodega_Consumible BC ON C.Id_consumible=BC.Id_consumible INNER JOIN Bodega B ON BC.Id_bodega=B.Id_bodega INNER JOIN Impresora I ON C.Id_impresora=I.Id_impresora AND BC.Id_bodega=$lugar GROUP BY C.Modelo ";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($data = mysqli_fetch_assoc($result)) {
                $arreglo["data"][] = array_map("utf8_encode", $data);
            }
        } else {
            $arreglo["data"][] = ["Id_consumible" => "", "Fecha" => "", "Marca" => "", "Modelo" => "", "Lugar" => "", "Tipo" => "", "Id_bodega" => "", "Impresora" => "", "Cantidad" => ""];
            //die("Error");
        }
        mysqli_free_result($result);
        $conn->close();
        return $arreglo;
    }

    /* Mostrar el contenido de la tabla consumible sin importar la bodega */
    public function showAll()
    {
        $conn = $this->conn->connect();

        $sql = "SELECT C.Id_consumible, C.Modelo, C.Marca, C.Tipo, CONCAT(I.Marca_impresora, ' ', I.Modelo_impresora) AS Impresora, COUNT(C.Modelo) AS Cantidad FROM Consumible C INNER JOIN Bodega_Consumible BC ON C.Id_consumible=BC.Id_consumible INNER JOIN Bodega B ON BC.Id_bodega=B.Id_bodega INNER JOIN Impresora I ON C.Id_impresora=I.Id_impresora GROUP BY C.Modelo ";

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
    /**@deprecated */
    /*  public function showSome($array)
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

        $sql = "SELECT T.Id_consumible, T.Marca, T.Modelo, T.Tipo, T.Codigo_barra, T.Id_impresora, T.Id_bodega, B.Lugar FROM Consumible T INNER JOIN Bodega B WHERE $condicion AND T.Id_bodega=B.Id_bodega";

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
    } */

    /**Funcion que comprueba que existan los modelos de consumibles antes de borrarlos*/
    public function deleteCon(int $cantidad, string $modelo, string $marca, string $tipo, int $id_bodega)
    {
        $conn = $this->conn->connect();
        /* Si es distinto a cero solo se borrarán algunos*/
        if ($cantidad != 0) {
            /* Comprobar la cantidad de consumibles en bodega */
            $sqlquery = "SELECT COUNT(C.Modelo) AS Cantidad FROM Consumible C INNER JOIN Bodega_Consumible BC ON C.Id_consumible=BC.Id_consumible AND BC.Id_bodega = $id_bodega AND C.Marca='$marca' AND C.Modelo='$modelo' AND C.Tipo='$tipo'";

            $result = $conn->query($sqlquery);

            if ($result->num_rows > 0) {
                while ($data = mysqli_fetch_assoc($result)) {
                    $arreglo = array_map("utf8_encode", $data);
                }

                if ($cantidad <=  $arreglo['Cantidad'] && $arreglo['Cantidad'] != 0) {
                    $res = self::deleteConsumables($cantidad, $marca, $tipo, $modelo, $id_bodega);
                }
            }
            mysqli_free_result($result);
            $conn->close();
            return $res;
        } else {
            return false;
        }
    }

    /* Borra modelos de consumibles segun cantidad*/
    public function deleteConsumables(int $cantidad, string $marca, string $tipo, string $modelo, int $bodega)
    {
        $conn = $this->conn->connect();

        $sqlquery = "SELECT C.Id_consumible FROM Consumible C INNER JOIN Bodega_Consumible BC ON C.Id_consumible=BC.Id_consumible  AND BC.Id_bodega = $bodega AND C.Marca='$marca' AND C.Modelo='$modelo' AND C.Tipo='$tipo' LIMIT $cantidad";

        $conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

        $result = $conn->query($sqlquery);
        $arreglo = [];
        if ($result->num_rows > 0) {
            while ($data = mysqli_fetch_assoc($result)) {
                array_push($arreglo, $data['Id_consumible']);
            }
            $valid = true;
            foreach ($arreglo as $id) {
                if ($conn->query("DELETE FROM Consumible WHERE Id_consumible=$id") === false) {
                    $valid = false;
                    $conn->rollback();
                    break;
                }
            }
        }
        mysqli_free_result($result);
        $conn->commit();
        $conn->close();
        return $valid;
    }

    /* deprecated */
    /* Borra todos los modelos de las bodegas */
    /*   private function deleteAll(string $marca, string $tipo, string $modelo)
    {
        $conn = $this->conn->connect();

        $sql = "DELETE FROM Consumible WHERE Modelo='$modelo' AND Marca='$marca' AND Tipo='$tipo'";

        $conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

        if ($conn->query($sql)) {
            $valid = true;
        } else {
            //echo "Error deleting record: " . $conn->error;
            $conn->rollback();
            $valid = false;
        }

        $conn->commit();
        $conn->close();
        return $valid;
    } */

    /**Comprueba que existan modelos de consumibles antes de transferirlos */
    public function transfer(int $cantidad, string $marca, string $modelo, string $tipo, int $origen, int $destino)
    {
        $conn = $this->conn->connect();

        if ($cantidad != 0) {

            $sqlquery = "SELECT COUNT(C.Modelo) AS Cantidad FROM Consumible C INNER JOIN Bodega_Consumible BC ON C.Id_consumible=BC.Id_consumible INNER JOIN Bodega B ON BC.Id_bodega=B.Id_bodega AND B.Id_bodega = $origen AND C.Marca='$marca' AND C.Modelo='$modelo' AND C.Tipo='$tipo'";

            $result = $conn->query($sqlquery);

            if ($result->num_rows > 0) {
                while ($data = mysqli_fetch_assoc($result)) {
                    $arreglo = array_map("utf8_encode", $data);
                }

                if ($cantidad <=  $arreglo['Cantidad']) {
                    $res = self::transferUpdate($cantidad,  $marca,  $tipo,  $modelo,  $origen,  $destino);
                }
            }

            mysqli_free_result($result);
            return $res;
        } else {
            return false;
        }
    }

    /**Modifica la bodega a la que pertenece el consumible */
    private function transferUpdate(int $cantidad, string $marca, string $tipo, string $modelo, int $bodega, int $codDestino)
    {

        $conn = $this->conn->connect();

        $sqlquery = "SELECT C.Id_consumible FROM Consumible C INNER JOIN Bodega_Consumible BC ON C.Id_consumible=BC.Id_consumible INNER JOIN Bodega B ON BC.Id_bodega=B.Id_bodega AND B.Id_bodega = $bodega AND C.Marca='$marca' AND C.Modelo='$modelo' AND C.Tipo='$tipo' LIMIT $cantidad";

        $conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

        $arreglo = [];
        $result = $conn->query($sqlquery);
        if ($result->num_rows > 0) {
            while ($data = mysqli_fetch_assoc($result)) {
                array_push($arreglo, $data['Id_consumible']);
            }
            $valid = true;
            foreach ($arreglo as $id) {
                if ($conn->query("UPDATE `Bodega_Consumible` SET `Id_bodega`=$codDestino WHERE Id_consumible=$id") === false) {
                    $valid = false;
                    $conn->rollback();
                    break;
                }
            }
        }
        mysqli_free_result($result);
        $conn->commit();
        $conn->close();
        return $valid;
    }

    /*  Actualizar modelos de consumibles */
    public function update(string $marca_new, string $modelo_new, string $tipo_new, int $impresora_new, string $marca_old, string $modelo_old, string $tipo_old, int $impresora_old)
    {
        $conn = $this->conn->connect();


        $sql = "UPDATE Consumible SET Marca='$marca_new', Modelo='$modelo_new', Tipo='$tipo_new', Id_impresora=$impresora_new WHERE Marca='$marca_old' AND Modelo='$modelo_old' AND Tipo='$tipo_old' AND Id_impresora=$impresora_old";


        if ($conn->query($sql)) {
            $valid = true;
        } else {
            $valid = false;
            //echo "Error updating record: " . $conn->error;
        }

        $conn->close();
        return $valid;
    }

    private function addListConsumible(string $marca, string $tipo, string $modelo, string $impresora)
    {
        $conn = $this->conn->connect();

        $query = "SELECT Id_impresora from Impresora WHERE CONCAT()";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            while ($data = $result->fetch_assoc()) {
                $arreglo = array_map('utf8_encode', $data);
            }
        } else {
            $valid = false;
        }
        $Id_impresora = (int) $arreglo['Id_impresora'];

        $sql = "INSERT INTO Consumible (Marca, Modelo, Tipo, Id_impresora) VALUES ('$marca', '$modelo', '$tipo', '$Id_impresora')";

        $conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

        $valid = true;

        for ($i = 0; $i < $cantidad; $i++) {

            if ($conn->query($sql)) {

                $last_idConsumible = $conn->insert_id;

                $sql2 = "INSERT INTO Bodega_Consumible (Id_bodega, Id_consumible) VALUES ($bodega, $last_idConsumible)";
                if ($conn->query($sql2) === false) {
                    $valid = false;
                    $conn->rollback();
                    //echo "Error: " . $sql . "<br>" . $conn->error;
                    break;
                }
            } else {
                //echo "Error: " . $sql . "<br>" . $conn->error;
                $conn->rollback();
                $valid = false;
                break;
            }
        }

        /* Enviar respuesta del proceso */
        if ($valid) {
            $arreglo = array('status' => 'ok');
            echo json_encode($arreglo);
        } else {
            $arreglo = array('status' => 'bad');
            echo json_encode($arreglo);
        }

        $conn->commit();
        $conn->close();
    }
}
