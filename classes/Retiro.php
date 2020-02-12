<?php

class Retiro
{

    public $conn;
    public $consumible;

    function __construct($db)
    {
        $this->conn = $db;
    }

    /* mostrar todos los retiros */
    function showAll()
    {
        $conn = $this->conn->connect();

        $sql = "SELECT DATE_FORMAT(Fecha, '%d/%m/%Y %H:%i:%s') AS Fecha, Usuario_retira as Retira, Usuario_recibe AS Recibe, Departamento, Marca, Modelo, Tipo, Cantidad, Impresora FROM Retiro ORDER BY Fecha DESC";
        $result =  $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($data = mysqli_fetch_assoc($result)) {
                $arreglo["data"][] = array_map("utf8_encode", $data);
            }
            echo json_encode($arreglo);
        } else {
            $arreglo["data"][] = ["Fecha" => "", "Retira" => "", "Recibe" => "", "Departamento" => "", "Marca" => "", "Modelo" => "", "Tipo" => "", "Cantidad" => "", "Impresora" => ""];
            // die("Error");
        }

        mysqli_free_result($result);
        $conn->close();
    }



    /* Retiros para informatica y manuel orella */
    public function insertWithdrawINF_MO(int $cantidad, string $usuarioRetira, string $usuarioRecibe, string $marca, string $modelo, string $tipo, string $impresora, string $bodega, string $idDirRecibe, string $idDepRecibe, string $idRecibe, string $nombreDepartamento, string $nombreBodega)
    {
        $conn = $this->conn->connect();


        $sql = "INSERT INTO Retiro (Usuario_retira, Usuario_recibe, Id_recibe, Id_departamento, Departamento, Marca, Modelo, Tipo, Cantidad, Impresora, Id_direccion, Bodega) VALUES ('$usuarioRetira','$usuarioRecibe','$idRecibe','$idDepRecibe','$nombreDepartamento','$marca','$modelo', '$tipo', '$cantidad', '$impresora','$idDirRecibe', '$nombreBodega')";

        if ($conn->query($sql)) {
            if ($this->consumible->deleteConsumables($cantidad, $marca, $tipo, $modelo, $bodega)) {
                $valid = true;
            }
        } else {
            $valid = false;
        }
        $conn->close();
        return $valid;
    }


    /**Filtrar retiros por mes */
    /**@deprecated */
    /*  public function filterByMonth(int $mes)
    {
        $conn = $this->conn->connect();
        $año = (int) date('Y');

        if (strlen($mes) === 1) {
            $mes = (int) "0" . $mes;
        }

        $sql = "SELECT DATE_FORMAT(Fecha, '%d/%m/%Y %H:%i:%s') AS Fecha, Usuario_retira as Retira, Usuario_recibe AS Recibe, Departamento, Marca, Modelo, Tipo, Cantidad, Impresora FROM Retiro WHERE MONTH(Fecha)=$mes AND YEAR(Fecha)=$año ORDER BY Fecha DESC";

        $result =  $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($data = mysqli_fetch_assoc($result)) {
                $arreglo["data"][] = array_map("utf8_encode", $data);
            }
            //  echo json_encode($arreglo);
        } else {
            $arreglo["data"][] = ["Fecha" => "", "Retira" => "", "Recibe" => "", "Departamento" => "", "Marca" => "", "Modelo" => "", "Tipo" => "", "Cantidad" => "", "Impresora" => ""];
            // die("Error");
        }
        echo json_encode($arreglo);
        mysqli_free_result($result);

        $conn->close();
    } */

    /**Filtrar retiros por rango de fecha */
    public function filterRangeHistorial(string $inicio, string $termino)
    {
        $conn = $this->conn->connect();

        $sql = "SELECT DATE_FORMAT(Fecha, '%d/%m/%Y %H:%i:%s') AS Fecha, Usuario_retira as Retira, Usuario_recibe AS Recibe, Departamento, Marca, Modelo, Tipo, Cantidad, Impresora FROM Retiro WHERE Fecha BETWEEN '$inicio' AND '$termino' ORDER BY Fecha DESC ";

        $result =  $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($data = mysqli_fetch_assoc($result)) {
                $arreglo["data"][] = array_map("utf8_encode", $data);
            }
        } else {
            $arreglo["data"][] = ["Fecha" => "", "Retira" => "", "Recibe" => "", "Departamento" => "", "Marca" => "", "Modelo" => "", "Tipo" => "", "Cantidad" => "", "Impresora" => ""];
            // die("Error");
        }
        echo json_encode($arreglo);
        mysqli_free_result($result);

        $conn->close();
    }

    /**Obtener la cantidad de consumibles retirados por los departamentos y que consumibles fueron retirados*/
    public function general_Report($inicio, $termino)
    {
        $general_Report['fecha_inicio'] = $inicio;
        $general_Report['fecha_termino'] = $termino;

        $general_Report['depart'] = self::department_Orders($inicio, $termino);

        /**Cantidad de consumibles retirados (consulta general) */
        $general_Report['model'] = self::count_Orders($inicio, $termino);

        return $general_Report;
    }


    /** cantidad de consumibles retirados por los departamentos*/
    private function department_Orders(string $inicio, string $termino)
    {
        $conn = $this->conn->connect();

        $sql = "SELECT DE.depart, COUNT(R.Departamento) AS Cantidad from departamentos DE INNER JOIN Retiro R ON DE.iddepart=R.Id_departamento WHERE R.Fecha BETWEEN '$inicio' AND '$termino' GROUP BY DE.depart ORDER BY Cantidad DESC";

        /*    $sql = "SELECT DE.depart, COUNT(R.Departamento) AS Cantidad from departamentos DE LEFT JOIN Retiro R ON DE.iddepart=R.Id_departamento and R.Fecha BETWEEN '2020/01/01' AND '2020/02/20' GROUP BY DE.depart ORDER BY `Cantidad` DESC "; */

        $result =  $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($data = mysqli_fetch_assoc($result)) {
                $arreglo["data"][] = array_map("utf8_encode", $data);
            }
        } else {
            // die("Error");
        }
        mysqli_free_result($result);

        $conn->close();

        if (isset($arreglo['data'])) {
            return $arreglo['data'];
        }
    }

    /**Cantidad de consumibles retirados (consulta general) */
    private function count_Orders(string $inicio, string $termino)
    {
        $conn = $this->conn->connect();

        $sql = "SELECT Marca, Modelo, Tipo, COUNT(Id_retiro) AS Cantidad from Retiro WHERE Fecha BETWEEN '$inicio' AND '$termino' GROUP BY Modelo ORDER BY Cantidad DESC";

        $result =  $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($data = mysqli_fetch_assoc($result)) {
                $arreglo["data"][] = array_map("utf8_encode", $data);
            }
        } else {
            // die("Error");
        }
        mysqli_free_result($result);

        $conn->close();

        if (isset($arreglo['data'])) {
            return $arreglo['data'];
        }
    }

    /**Obtener informacion de las direcciones*/
    public function getDir()
    {
        $conn = $this->conn->connect();

        $sql = "SELECT iddireccion, direccion FROM direcciones";

        $result =  $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($data = mysqli_fetch_assoc($result)) {
                $arreglo["data"][] = array_map("utf8_encode", $data);
            }
        } else {
            // die("Error");
        }
        mysqli_free_result($result);

        $conn->close();

        if (isset($arreglo['data'])) {
            return $arreglo['data'];
        }
    }

    public function getDepart(int $iddir, string $inicio, string $termino)
    {
        $conn = $this->conn->connect();

        $sql = "SELECT iddepart AS Id_dep, depart AS Departamento FROM departamentos where direccion=$iddir";

        $result =  $conn->query($sql);


        if ($result->num_rows > 0) {
            while ($data = mysqli_fetch_assoc($result)) {
                $dep[] = array_map("utf8_encode", $data);
            }
        }

        foreach ($dep as $key => $depValue) {

            $id = (int) $depValue['Id_dep'];

            /* $sql = "SELECT DATE_FORMAT(Fecha, '%d/%m/%Y %H:%i:%s') AS Fecha, Usuario_recibe, Marca, Modelo, Tipo, Cantidad FROM Retiro WHERE Fecha BETWEEN '$inicio' AND '$termino' AND Id_departamento=$id"; */
            $sql = "SELECT  Marca, COUNT(Modelo) AS Cantidad, Modelo, Tipo FROM Retiro WHERE Fecha BETWEEN '$inicio' AND '$termino' AND Id_departamento=$id GROUP BY Modelo";

            $result =  $conn->query($sql);

            echo "----------------------------------------------------" . $depValue['Departamento'] . "---------------------------------------------------------";
            // var_dump($result->num_rows);
            if ($result->num_rows > 0) {
                while ($data = mysqli_fetch_assoc($result)) {
                    $Retiro_dep[$depValue['Departamento']][] = array_map("utf8_encode", $data);
                }
                echo json_encode($Retiro_dep[$depValue['Departamento']]);
            } else {
                $Retiro_dep[$depValue['Departamento']][]  = "-------------->>No hay nada---------------";
                echo json_encode($Retiro_dep[$depValue['Departamento']]);
            }
        }
        mysqli_free_result($result);

        $conn->close();

        /* if (isset($Retiro_dep)) {
            return $Retiro_dep;
        } */
    }
}
