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
$retiro = new Retiro($db);


$inicio = $_GET['inicio'];
$termino = $_GET['termino'];

$general_Report = $retiro->general_Report($inicio, $termino);

$inicio = explode('-', $inicio);
$termino = explode('-', $termino);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte General</title>
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
            font-size: 1.8em;
        }

        h2 {
            font-size: 1em;
        }
    </style>

    <!-- <style type="text/css">

    
        thead:before,
        thead:after {
            display: none;
        }

        tbody:before,
        tbody:after {
            display: none;
        }

        html {
            margin: 0;
        }

        body {
            font-family: "Helvetica", serif;
            margin: 0;
        }

    </style>
 -->
</head>

<body>
    <div id="header">
        <h1 class="text-center mb-5 mt-5">
            <font><b>Entregas <?php echo $inicio[2] . '-' . $inicio[1] . '-' . $inicio[0] . ' a ' . $termino[2] . '-' . $termino[1] . '-' . $termino[0] ?> </b></font>
        </h1>
    </div>

    <div id="content">
        <!--  <br><br><br><br><br> -->
        <h2 class="text-center">
            <font face="helvetica"><b>Departamentos</b></font>
        </h2>

        <div class="row">
            <div class="mx-auto col-10">
                <div class="table-responsive-sm text-center ">
                    <table class="table table-sm table-hover table-striped table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="p-0 px-2">Departamento</th>
                                <th scope="col" class="p-0 px-2">Cant. de elementos entregados</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-depart">
                            <?php
                            foreach ($general_Report['depart'] as $value) {
                                echo '<tr><td  scope="col" class="p-0 px-2">' . strtoupper($value['depart'])  . '</td><td scope="col" class="p-0 px-2">' . $value['Cantidad'] . '</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                    <caption class="text-left"><small>Emitido el <?php echo date("d-m-Y") ?></small> </caption>
                </div>
            </div>
        </div>

        <div style="page-break-after:always;"></div>
        <h2 class="text-center"><b>Consumibles<b></h2>
        <div class="row mt-5">
            <div class="mx-auto col-10">
                <table class="table table-sm table-striped table-bordered text-center">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col" class="p-0">Marca</th>
                            <th scope="col" class="p-0">Modelo</th>
                            <th scope="col" class="p-0">Tipo</th>
                            <th scope="col" class="p-0">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-model">
                        <?php
                        foreach ($general_Report['model'] as $value) {
                            echo '<tr><td  scope="col" class="p-0 px-2">' . $value['Marca'] . '</td><td scope="col" class="p-0 px-2">' . $value['Modelo'] . '</td><td scope="col" class="p-0 px-2">' . $value['Tipo'] . '</td><td scope="col" class="p-0 px-2">' . $value['Cantidad'] . '</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
                <caption class="text-left"><small>Emitido el <?php echo date("d-m-Y") ?></small> </caption>
            </div>
        </div>

    </div>







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

</html>';
?>
<?php


$pdf->set_paper("A4", "portrait");

//$pdf->set_option('defaultFont', 'Helvetica');

$pdf->load_html(ob_get_clean());

$pdf->render();

$pdf->stream('document.pdf', array('Attachment' => 0));
?>