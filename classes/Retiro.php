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

        $sql = "SELECT DATE_FORMAT(Fecha, '%d-%m-%Y') AS Fecha, Usuario_retira as Retira, Usuario_entrega AS Entrega, Departamento, Marca, Modelo, Tipo, Codigo_barra AS Codigo, Bodega FROM Retiro ORDER BY Fecha DESC";
        $result =  $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($data = mysqli_fetch_assoc($result)) {
                $arreglo["data"][] = array_map("utf8_encode", $data);
            }
            echo json_encode($arreglo);
        } else {
            /* $arreglo["data"][]=["Fecha"=>"", "Retira"=>"", "Entrega"=>"","Marca"=>"","Modelo"=>"","Tipo"=>"", "Codigo"=>"", "Lugar"=>""]; */
            die("Error");
        }

        /* $result = mysqli_query($conn, $sql);
        if(!$result){
            die("Error");
        }else{
            while($data = mysqli_fetch_assoc($result)){
                $arreglo["data"][]=array_map("utf8_encode", $data);  
            }
            echo json_encode($arreglo);
        }      
        mysqli_close($result); */
        mysqli_free_result($result);
        $conn->close();
    }

    public function insertWithdraw(string $usuarioRetira, string $usuarioRecibe, string $departamento, string $marca, string $modelo, string $tipo, string $codigoBarra, string $bodega, int $id)
    {
        $conn = $this->conn->connect();

        $sql = "INSERT INTO Retiro (Usuario_retira, Usuario_entrega, Departamento, Marca, Modelo, Tipo, Codigo_barra, Bodega) VALUES ('$usuarioRetira','$usuarioRecibe','$departamento','$marca','$modelo', '$tipo','$codigoBarra','$bodega')";

        if ($conn->query($sql)) {
            $this->consumible->delete($id);
        } else {
            $arreglo = array('status' => 'bad');
            echo json_encode($arreglo);
            //echo "Error: " . $sql . "<br>" . $conn->error;
        }
        $conn->close();
    }
}
