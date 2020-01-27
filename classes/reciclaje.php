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