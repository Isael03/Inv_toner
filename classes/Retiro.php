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

    public function getDepartament(string $inicio, string $termino)
    {
        $conn = $this->conn->connect();

        $sql = "SELECT Id_departamento, Departamento FROM Retiro WHERE Fecha BETWEEN '$inicio' AND '$termino' GROUP BY Departamento";

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
}
