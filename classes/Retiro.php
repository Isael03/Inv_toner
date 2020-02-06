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

    /* Retiro desde cualquier bodega */
    public function insertWithdraw(int $cantidad, string $usuarioRetira, string $usuarioRecibe,  string $marca, string $modelo, string $tipo, string $impresora, string $idDirRecibe, string $idDepRecibe, string $idRecibe, string $nombreDepartamento)
    {
        $conn = $this->conn->connect();

        $sql = "INSERT INTO Retiro (Usuario_retira, Usuario_recibe, Id_recibe, Id_departamento, Departamento, Marca, Modelo, Tipo, Cantidad, Impresora, Id_direccion) VALUES ('$usuarioRetira','$usuarioRecibe','$idRecibe','$idDepRecibe','$nombreDepartamento','$marca','$modelo', '$tipo', '$cantidad', '$impresora','$idDirRecibe')";


        if ($conn->query($sql)) {
            $this->consumible->deleteCon($cantidad, $modelo, $marca, $tipo);
            /*   $arreglo = array('status' => 'ok');
            echo json_encode($arreglo); */
        } else {
            $arreglo = array('status' => 'bad');
            echo json_encode($arreglo);
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        $conn->close();
    }

    /* Retiros para informatica y manuel orella */
    public function insertWithdrawINF_MO(int $cantidad, string $usuarioRetira, string $usuarioRecibe, string $marca, string $modelo, string $tipo, string $impresora, string $bodega, string $idDirRecibe, string $idDepRecibe, string $idRecibe, string $nombreDepartamento)
    {
        $conn = $this->conn->connect();


        $sql = "INSERT INTO Retiro (Usuario_retira, Usuario_recibe, Id_recibe, Id_departamento, Departamento, Marca, Modelo, Tipo, Cantidad, Impresora, Id_direccion) VALUES ('$usuarioRetira','$usuarioRecibe','$idRecibe','$idDepRecibe','$nombreDepartamento','$marca','$modelo', '$tipo', '$cantidad', '$impresora','$idDirRecibe')";

        if ($conn->query($sql)) {
            $this->consumible->deleteConsumables($cantidad, $marca, $tipo, $modelo, $bodega);
            $arreglo = array('status' => 'ok');
            echo json_encode($arreglo);
        } else {
            $arreglo = array('status' => 'bad');
            echo json_encode($arreglo);
            //echo "Error: " . $sql . "<br>" . $conn->error;
        }
        $conn->close();
    }

    public function filterByMonth(int $mes)
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
    }

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

    public function general_Report($inicio, $termino)
    {
        $general_Report['fecha_inicio'] = $inicio;
        $general_Report['fecha_termino'] = $termino;
        $general_Report['depart'] = self::department_Orders($inicio, $termino);
        $general_Report['model'] = self::count_Orders($inicio, $termino);

        return $general_Report;
    }

    private function department_Orders(string $inicio, string $termino)
    {
        $conn = $this->conn->connect();

        $sql = "SELECT DE.depart, COUNT(R.Departamento) AS Cantidad from departamentos DE INNER JOIN Retiro R ON DE.iddepart=R.Id_departamento WHERE R.Fecha BETWEEN '$inicio' AND '$termino' GROUP BY DE.depart ORDER BY Cantidad DESC";

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

    public function getDir(string $inicio, string $termino)
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
