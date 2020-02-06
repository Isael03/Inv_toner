<?php
ob_start();
require_once '../vendor/dompdf/autoload.inc.php';
include "../config/Database.php";
include "../classes/Retiro.php";

use Dompdf\Dompdf;

$pdf = new DOMPDF();

$pdf->set_option('isHtml5ParserEnabled', true);
//$pdf->set_option('isPhpEnabled', true);

$db = new ConexionData();

$inicio_dep = $_GET['inicio_dep'];
$termino_dep = $_GET['termino_dep'];
$nombre_dir = $_GET['nombre_dir'];
$iddir = $_GET['iddir'];

$inicio = explode('-', $inicio_dep);
$termino = explode('-', $termino_dep);

$conn = $db->connect();

$sql = "SELECT iddepart AS Id_dep, depart AS Departamento FROM departamentos where direccion=$iddir";

$result =  $conn->query($sql);

if ($result->num_rows > 0) {
    while ($data = mysqli_fetch_assoc($result)) {
        $dep[] = array_map("utf8_encode", $data);
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Dirección</title>
    <link href="../resources/css/sb-admin.css" rel="stylesheet">
    <style type="text/css">
        @page {
            margin: 130px 50px;
        }

        #header {
            position: fixed;
            left: 0px;
            top: -130px;
            right: 0px;
            height: 0;
            text-align: center;
        }



        thead:before,
        thead:after {
            display: none;
        }

        tbody:before,
        tbody:after {
            display: none;
        }

        h1 {
            font-size: 1em;
        }

        h2 {
            font-size: .8em;
        }
    </style>

</head>

<body>

    <div id="header"">
        <h1 class=" text-center mt-5" id="title_report">
        <b>Entregas a la Dirección de <?php echo strtoupper($nombre_dir) . " " .  $inicio[2] . '-' . $inicio[1] . '-' . $inicio[0] . ' a ' . $termino[2] . '-' . $termino[1] . '-' . $termino[0] ?> </b>
        <caption class="text-center"><small>Emitido el <?php echo date("d-m-Y") ?></small> </caption>
        </h1>
    </div>


    <?php
    foreach ($dep as $key => $depValue) {
    ?>
        <div id="content">
            <div class="row ">
                <div class="mx-auto col-10">
                    <h2 class=" text-center mb-2"><b><?php echo strtoupper($depValue['Departamento']); ?></b></h2>
                    <div class="table-responsive-sm text-center ">
                        <table class="table table-sm table-hover table-striped table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="p-0 px-2">Marca</th>
                                    <th scope="col" class="p-0 px-2">Modelo</th>
                                    <th scope="col" class="p-0 px-2">Tipo</th>
                                    <th scope="col" class="p-0 px-2">Cantidad</th>
                                </tr>
                            </thead>

                            <tbody id="tbody-depart">
                                <?php

                                $id = (int) $depValue['Id_dep'];

                                $sql = "SELECT  Marca, COUNT(Modelo) AS Cantidad, Modelo, Tipo FROM Retiro WHERE Fecha BETWEEN '$inicio_dep' AND '$termino_dep' AND Id_departamento=$id GROUP BY Modelo";

                                /*       $sql = "SELECT DATE_FORMAT(Fecha, '%d/%m/%Y %H:%i:%s') AS Fecha, Usuario_recibe, Marca, Modelo, Tipo, Cantidad FROM Retiro WHERE Fecha BETWEEN '$inicio_dep' AND '$termino_dep' AND Id_departamento=$id"; */

                                $result =  $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($data = mysqli_fetch_assoc($result)) {
                                        $Retiro_dep[$depValue['Departamento']][] = array_map("utf8_encode", $data);
                                    }

                                    foreach ($Retiro_dep[$depValue['Departamento']] as  $deptable) {
                                        echo "<tr><td scope='col' class='p-0 px-2'>" . $deptable['Marca'] . "</td><td scope='col' class='p-0 px-2'>" . $deptable['Modelo'] . "</td><td scope='col' class='p-0 px-2'>" . $deptable['Tipo'] . "</td><td scope='col' class='p-0 px-2'>" . $deptable['Cantidad'] . "</td></tr>";
                                    }
                                } else {

                                    echo "<tr><td colspan='4'>No se encontraron datos</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php echo "<br><br>";
    } ?>

    <div style="page-break-after:never;"></div>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../resources/js/sb-admin.min.js"></script>

    <script type="text/php"> // (activate dompdf option isPhpEnabled)
  if (isset($pdf))
  {
    $font = $fontMetrics->get_font("helvetica", "bold");
    $size = 8;
    $color = array(0,0,0);
    $word_space = 0.0;  //  default
    $char_space = 0.0;  //  default
    $angle = 0.0;   //  default
    //
    $text = "Página {PAGE_NUM} de {PAGE_COUNT}";
    $text_width = $fontMetrics->getTextWidth($text, $font, $size);
    //
    $x = 690 - $text_width;
    $y = 825;
    $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
  }
</script>

</body>

</html>

<?php

$pdf->set_paper("A4", "portrait");

//$pdf->set_option('defaultFont', 'Helvetica');

$pdf->load_html(ob_get_clean());

$pdf->render();

$pdf->stream('document.pdf', array('Attachment' => 0));
?>