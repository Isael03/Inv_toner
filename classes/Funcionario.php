<?php

class Funcionario
{

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**Listar nombres y apellidos de los funcionarios */
    public function search(string $texto)
    {

        $text = $texto . "%";
        $result = $this->conn->connect()->query("SELECT CONCAT(nombres,' ', apellidos) AS Nombres FROM `funcionarios` WHERE nombres LIKE '$text'");

        $arreglo = [];
        if ($result->num_rows > 0) {
            while ($data = $result->fetch_assoc()) {
                array_push($arreglo, $data['Nombres']);
            }
        } else {
            echo "0 results";
        }

        echo json_encode($arreglo);


        mysqli_free_result($result);
        $this->conn->connect()->close();
    }

    /**Obtener informacion del funcionario con el nombre+apellido */
    public function officialData(string $nombre)
    {
        $nameArray = explode(' ', $nombre);
        $name = "";

        foreach ($nameArray as $namePart) {
            $name .= " " . trim($namePart);
        }
        $name = trim($name);
        //$name = $name;
        $conn = $this->conn->connect();

        $sql = "SELECT F.id AS IdFuncionario, F.rut, F.direccion AS IdDireccion ,F.depart AS ID_departamento, DE.depart AS Departamento FROM funcionarios F INNER JOIN departamentos DE ON F.depart=DE.iddepart INNER JOIN direcciones DI ON F.direccion=DI.iddireccion WHERE CONCAT(F.nombres, F.apellidos) LIKE '$name'";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($data = mysqli_fetch_assoc($result)) {
                $arreglo = array_map("utf8_encode", $data);
            }
        } else {
            //die("Error");
            /**
             * si la primera query no reconoce el $name se intentará esta para obtener los datos
             */
            $sql = "SELECT F.id AS IdFuncionario, F.rut, F.direccion AS IdDireccion ,F.depart AS ID_departamento, DE.depart AS Departamento FROM funcionarios F INNER JOIN departamentos DE ON F.depart=DE.iddepart INNER JOIN direcciones DI ON F.direccion=DI.iddireccion WHERE CONCAT(TRIM(F.nombres),' ',TRIM(F.apellidos)) LIKE '$name'";

            $result = $conn->query($sql);
            while ($data = mysqli_fetch_assoc($result)) {
                $arreglo = array_map("utf8_encode", $data);
            }
        }

        mysqli_free_result($result);
        $conn->close();


        if (isset($arreglo)) {
            return $arreglo;
        }
    }
}
