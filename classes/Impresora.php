<?php
class Impresora
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }


    public function insertPrinter(string $marca, string $modelo)
    {
        $conn = $this->conn->connect();

        $sql = "INSERT INTO Impresora VALUES (NULL, '$marca', '$modelo')";

        if ($conn->query($sql)) {
            $valid = true;
        } else {
            $valid = false;
        }
        $conn->close();
        return $valid;
    }

    public function showPrinters()
    {
        $conn = $this->conn->connect();

        $sql = "SELECT Id_impresora, Marca_impresora, Modelo_impresora from Impresora";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {

            while ($data = $result->fetch_assoc()) {
                $arreglo['data'][] = array_map('utf8_encode', $data);
            }
        }

        $conn->close();
        if (isset($arreglo['data'])) {
            return $arreglo;
        }
    }

    public function updatePrinter(string $marca, string $modelo, int $id)
    {
        $conn = $this->conn->connect();

        $sql = "UPDATE Impresora SET Marca_impresora='$marca', Modelo_impresora='$modelo' WHERE Id_impresora=$id";

        if ($conn->query($sql)) {
            $valid = true;
        } else {
            $valid = false;
            //echo "Error updating record: " . $conn->error;
        }
        $conn->close();
        return $valid;
    }


    public function deletePrinter(int $id)
    {
        $conn = $this->conn->connect();

        $sql = "DELETE FROM Impresora WHERE Id_impresora=$id";

        if ($conn->query($sql)) {
            $valid = true;
        } else {
            $valid = false;
            //echo "Error updating record: " . $conn->error;
        }

        $conn->close();
        return $valid;
    }

    public function printersBrand()
    {
        $conn = $this->conn->connect();

        $sql = "SELECT Modelo from Consumible GROUP BY Modelo";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($data = $result->fetch_assoc()) {
                $arreglo['modelo_con'][] = array_map('utf8_encode', $data);
            }
        }

        $sql = "SELECT Marca_impresora from Impresora GROUP BY Marca_impresora";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($data = $result->fetch_assoc()) {
                $arreglo['marca'][] = array_map('utf8_encode', $data);
            }
        }

        $conn->close();
        if (isset($arreglo['marca'])) {
            return $arreglo;
        }
    }


    public function showModelPrinter(string $marca)
    {
        $conn = $this->conn->connect();

        $sql = "SELECT Modelo_impresora from Impresora WHERE Marca_impresora='$marca' GROUP BY Modelo_impresora";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($data = $result->fetch_assoc()) {
                $arreglo[] = array_map('utf8_encode', $data);
            }
        }

        $conn->close();
        if (isset($arreglo)) {
            return $arreglo;
        }
    }

    public function showId(string $impresora)
    {
        $conn = $this->conn->connect();

        $sql = "SELECT Id_impresora from Impresora WHERE CONCAT(Marca_impresora, ' ', Modelo_impresora)='$impresora'";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {

            while ($data = $result->fetch_assoc()) {
                $arreglo = array_map('utf8_encode', $data);
            }
        }

        $conn->close();
        if (isset($arreglo)) {
            return $arreglo['Id_impresora'];
        }
    }

    public function NamePrinter(string $marca)
    {
        $conn = $this->conn->connect();

        $sql = "SELECT CONCAT(Marca_impresora, ' ', Modelo_impresora) AS Impresora from Impresora WHERE Marca_impresora='$marca'";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {

            while ($data = $result->fetch_assoc()) {
                $arreglo[] = array_map('utf8_encode', $data);
            }
        }

        $conn->close();
        if (isset($arreglo)) {
            return $arreglo;
        }
    }
}
