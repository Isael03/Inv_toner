<?php

class Retiro
{

    public $conn;
    public $consumible;

    function __construct($db)
    {
        $this->conn = $db;
    }
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

    public function insertWithdraw(int $cantidad, string $usuarioRetira, string $usuarioRecibe, string $departamento,  string $marca, string $modelo, string $tipo, string $impresora)
    {
        $conn = $this->conn->connect();

        $sql = "INSERT INTO Retiro (Usuario_retira, Usuario_recibe, Departamento, Marca, Modelo, Tipo, Cantidad, Impresora) VALUES ('$usuarioRetira','$usuarioRecibe','$departamento','$marca','$modelo', '$tipo', '$cantidad', '$impresora')";


        if ($conn->query($sql)) {
            $this->consumible->deleteCon($cantidad, $modelo, $marca, $tipo);
        } else {
            $arreglo = array('status' => 'bad');
            echo json_encode($arreglo);
            //echo "Error: " . $sql . "<br>" . $conn->error;
        }
        $conn->close();
    }

    public function insertWithdrawINF_MO(int $cantidad, string $usuarioRetira, string $usuarioRecibe, string $departamento,  string $marca, string $modelo, string $tipo, string $impresora, string $bodega)
    {
        $conn = $this->conn->connect();

        $sql = "INSERT INTO Retiro (Usuario_retira, Usuario_recibe, Departamento, Marca, Modelo, Tipo, Cantidad, Impresora) VALUES ('$usuarioRetira','$usuarioRecibe','$departamento','$marca','$modelo', '$tipo', '$cantidad', '$impresora')";

        if ($conn->query($sql)) {
            $this->consumible->deleteConsumables($cantidad, $marca, $tipo, $modelo, $bodega);
        } else {
            $arreglo = array('status' => 'bad');
            echo json_encode($arreglo);
            //echo "Error: " . $sql . "<br>" . $conn->error;
        }
        $conn->close();
    }
}
