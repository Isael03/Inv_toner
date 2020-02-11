<?php
/* Eliminar */
public function delete(int $Id_consumible)
{
$conn = $this->conn->connect();

$sql = "DELETE FROM Consumible WHERE Id_consumible=$Id_consumible";

if ($conn->query($sql)) {
$arreglo = array("status" => "ok");
echo json_encode($arreglo);
} else {
//echo "Error deleting record: " . $conn->error;
$arreglo = array("status" => "bad");
echo json_encode($arreglo);
}

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