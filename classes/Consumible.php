<?php

class Consumible
{
    public $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**Añadir solo cantidades de consumibles */
    public function addConsumablesExists(int $cantidad, int $Id_consumible, int $Id_bodega)
    {

        $conn = $this->conn->connect();
        $valid = true;
        $conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

        $sql2 = "INSERT INTO Bodega_Consumible (Id_bodega, Id_consumible) VALUES ($Id_bodega, $Id_consumible)";
        for ($i = 0; $i < $cantidad; $i++) {
            if ($conn->query($sql2) === false) {
                $valid = false;
                $conn->rollback();
                //echo "Error: " . $sql . "<br>" . $conn->error;
                break;
            }
        }
        $conn->commit();
        $conn->close();
        return $valid;
    }

    /* Insertar nuevos Consumibles  */
    public function addPrinterConsumables(int $cantidad, string $marca, string $tipo, string $modelo, int $bodega, int $Id_impresora, int $rangoMinimo = 0, int $rangoMaximo = 0)
    {
        $conn = $this->conn->connect();
        $valid = true;

        $query = "SELECT Id_consumible from Consumible WHERE Marca='$marca' AND Modelo='$modelo' AND Tipo='$tipo' AND Id_impresora=$Id_impresora";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {

            while ($data = $result->fetch_assoc()) {
                $arreglo = array_map('utf8_encode', $data);
            }

            $conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

            $last_idConsumible = $arreglo['Id_consumible'];
            $sql2 = "INSERT INTO Bodega_Consumible (Id_bodega, Id_consumible) VALUES ($bodega, $last_idConsumible)";
            for ($i = 0; $i < $cantidad; $i++) {
                if ($conn->query($sql2) === false) {
                    $valid = false;
                    $conn->rollback();
                    echo "Error: " . $sql2 . "<br>" . $conn->error;
                    break;
                }
            }
        } else {
            $sql = "INSERT INTO Consumible (Marca, Modelo, Tipo, Id_impresora, rango_stockMinimo, rango_stockMaximo) VALUES ('$marca', '$modelo', '$tipo', $Id_impresora, $rangoMinimo, $rangoMaximo)";

            $conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

            if ($conn->query($sql)) {
                $last_idConsumible = $conn->insert_id;
                $sql2 = "INSERT INTO Bodega_Consumible (Id_bodega, Id_consumible) VALUES ($bodega, $last_idConsumible)";
                for ($i = 0; $i < $cantidad; $i++) {
                    if ($conn->query($sql2) === false) {
                        $valid = false;
                        $conn->rollback();
                        // echo "Error: " . $sql . "<br>" . $conn->error;
                        break;
                    }
                }
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
                // $conn->rollback();
                $valid = false;
            }
        }

        /* Enviar respuesta del proceso */
        /*  if ($valid) {
        $arreglo = array('status' => 'ok');
        echo json_encode($arreglo);
        } else {
        $arreglo = array('status' => 'bad');
        echo json_encode($arreglo);
        } */

        $conn->commit();
        $conn->close();
        return $valid;
    }

    /**Obtener el contenido de cada bodega*/
    public function getConsumiblesStorage(int $lugar = null)
    {
        $conn = $this->conn->connect();

        $sql = "SELECT C.Id_consumible, C.Modelo, C.Marca, C.Tipo, CONCAT(I.Marca_impresora, ' ', I.Modelo_impresora) AS Impresora, C.Id_impresora, COUNT(C.Modelo) AS Cantidad, B.Lugar, BC.Id_bodega  FROM Consumible C INNER JOIN Bodega_Consumible BC ON C.Id_consumible=BC.Id_consumible INNER JOIN Bodega B ON BC.Id_bodega=B.Id_bodega INNER JOIN Impresora I ON C.Id_impresora=I.Id_impresora AND BC.Id_bodega=$lugar GROUP BY C.Modelo ";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($data = mysqli_fetch_assoc($result)) {
                $arreglo["data"][] = array_map("utf8_encode", $data);
            }
        } else {
            $arreglo["data"] = ["Id_consumible" => "", "Fecha" => "", "Marca" => "", "Modelo" => "", "Lugar" => "", "Tipo" => "", "Id_bodega" => "", "Impresora" => "", "Cantidad" => ""];
            //die("Error");
        }
        mysqli_free_result($result);
        $conn->close();
        return $arreglo;
    }

    /**Mostrar solo los consumibles  */
    public function showListConsumable()
    {
        $conn = $this->conn->connect();

        $sql = "SELECT C.Id_consumible, C.Modelo, C.Marca, C.Tipo, CONCAT(I.Marca_impresora, ' ', I.Modelo_impresora) AS Impresora, I.Id_impresora, C.rango_stockMinimo AS Minimo, C.rango_stockMaximo AS Maximo FROM Consumible C INNER JOIN Impresora I ON C.Id_impresora=I.Id_impresora";

        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($data = mysqli_fetch_assoc($result)) {
                $arreglo["data"][] = array_map("utf8_encode", $data);
            }
        } else {
            //$arreglo["data"][] = ["Id_consumible" => "", "Marca" => "", "Modelo" => "",  "Tipo" => "", "Impresora" => ""];
            $arreglo["data"][] = [];

            //die("Error");
        }
        mysqli_free_result($result);
        $conn->close();

        return $arreglo;
    }

    /* Mostrar el contenido de la tabla consumible incluyendo cantidades de stock sin importar la bodega */
    public function showAll()
    {
        $conn = $this->conn->connect();

        $sql = "SELECT C.Id_consumible, C.Modelo, C.Marca, C.Tipo, CONCAT(I.Marca_impresora, ' ', I.Modelo_impresora) AS Impresora, COUNT(BC.Id_consumible) AS Cantidad, C.rango_stockMinimo AS Minimo, C.rango_stockMaximo AS Maximo, CASE WHEN COUNT(BC.Id_consumible) > C.rango_stockMaximo THEN 'Suficientes' WHEN COUNT(BC.Id_consumible) < C.rango_stockMinimo THEN 'Insuficientes' ELSE 'Dentro de lo permitido' END AS Estado FROM Consumible C LEFT JOIN Bodega_Consumible BC ON C.Id_consumible=BC.Id_consumible LEFT JOIN Bodega B ON BC.Id_bodega=B.Id_bodega LEFT JOIN Impresora I ON C.Id_impresora=I.Id_impresora GROUP BY C.Id_consumible";

        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($data = mysqli_fetch_assoc($result)) {
                $arreglo["data"][] = array_map("utf8_encode", $data);
            }
        } else {
            $arreglo["data"] = [];

            //die("Error");
        }
        echo json_encode($arreglo);
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
    public function deleteCon(int $cantidad, int $id_bodega, int $Id_consumible)
    {
        $conn = $this->conn->connect();

        /* Si es distinto a cero solo se borrarán algunos*/
        if ($cantidad != 0) {
            /* Comprobar la cantidad de consumibles en bodega */

            $sqlquery = "SELECT COUNT(Id_ubicacion) AS Cantidad FROM Bodega_Consumible WHERE Id_consumible=$Id_consumible AND Id_bodega=$id_bodega";

            $result = $conn->query($sqlquery);

            if ($result->num_rows > 0) {
                while ($data = mysqli_fetch_assoc($result)) {
                    $arreglo = array_map("utf8_encode", $data);
                }

                if ($cantidad <= $arreglo['Cantidad'] && $arreglo['Cantidad'] != 0) {
                    $res = self::deleteConsumables($cantidad, $id_bodega, $Id_consumible);
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
    public function deleteConsumables(int $cantidad, int $bodega, int $Id_consumible)
    {
        $conn = $this->conn->connect();

        $sqlquery = "SELECT Id_ubicacion FROM Bodega_Consumible WHERE Id_consumible=$Id_consumible AND Id_bodega=$bodega LIMIT $cantidad";

        $conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

        $result = $conn->query($sqlquery);
        $arreglo = [];
        if ($result->num_rows > 0) {
            while ($data = mysqli_fetch_assoc($result)) {
                array_push($arreglo, $data['Id_ubicacion']);
            }
            $valid = true;
            foreach ($arreglo as $id) {
                if ($conn->query("DELETE FROM Bodega_Consumible WHERE Id_ubicacion=$id AND Id_bodega=$bodega") === false) {
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

    /* Borra todos los modelos de las bodegas */
    public function deleteAll(int $Id_consumible)
    {
        $conn = $this->conn->connect();

        $sql = "DELETE FROM Consumible WHERE Id_consumible=$Id_consumible";

        if ($conn->query($sql)) {
            $valid = true;
        } else {
            //echo "Error deleting record: " . $conn->error;
            $valid = false;
        }
        $conn->close();
        return $valid;
    }

    /**Comprueba que existan modelos de consumibles antes de transferirlos */
    public function transfer(int $cantidad, int $origen, int $destino, int $Id_consumible)
    {
        $conn = $this->conn->connect();

        if ($cantidad != 0) {

            $sqlquery = "SELECT COUNT(Id_ubicacion) AS Cantidad FROM Bodega_Consumible WHERE Id_consumible=$Id_consumible AND Id_bodega=$origen";

            $result = $conn->query($sqlquery);

            if ($result->num_rows > 0) {
                while ($data = mysqli_fetch_assoc($result)) {
                    $arreglo = array_map("utf8_encode", $data);
                }

                if ($cantidad <= $arreglo['Cantidad']) {
                    $res = self::transferUpdate($cantidad, $origen, $destino, $Id_consumible);
                }
            }

            mysqli_free_result($result);
            return $res;
        } else {
            return false;
        }
    }

    /**Modifica la bodega a la que pertenece el consumible */
    private function transferUpdate(int $cantidad, int $bodega, int $codDestino, int $Id_consumible)
    {

        $conn = $this->conn->connect();

        $sqlquery = "SELECT Id_ubicacion FROM Bodega_Consumible WHERE Id_consumible=$Id_consumible AND Id_bodega=$bodega LIMIT $cantidad";

        $conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

        $arreglo = [];
        $result = $conn->query($sqlquery);
        if ($result->num_rows > 0) {
            while ($data = mysqli_fetch_assoc($result)) {
                array_push($arreglo, $data['Id_ubicacion']);
            }

            $valid = true;
            foreach ($arreglo as $id) {
                if ($conn->query("UPDATE `Bodega_Consumible` SET `Id_bodega`=$codDestino WHERE Id_ubicacion=$id") === false) {
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
    public function update(string $marca_new, string $modelo_new, string $tipo_new, int $impresora_new, int $Id_consumible, int $rangoMinimo, int $rangoMaximo)
    {
        $conn = $this->conn->connect();

        $sql = "UPDATE Consumible SET Marca='$marca_new', Modelo='$modelo_new', Tipo='$tipo_new', Id_impresora=$impresora_new, rango_stockMinimo=$rangoMinimo, rango_stockMaximo=$rangoMaximo WHERE Id_consumible=$Id_consumible";

        if ($conn->query($sql)) {
            $valid = true;
        } else {
            $valid = false;
            //echo "Error updating record: " . $conn->error;
        }

        $conn->close();
        return $valid;
    }
}
