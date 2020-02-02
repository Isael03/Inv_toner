<?php

require('../vendor/fpdf/fpdf.php');


class PDF extends FPDF
{

    // Tabla coloreada
    function department_Count($header, $data)
    {
        $this->setX(20);
        $this->Cell(100, 7, 'Departamentos', 0, 1, 'R');
        $this->setY(45);

        $this->SetTextColor(255);
        $this->SetLineWidth(.3);
        $this->SetFont('', 'B');

        $this->setX(20);
        //$fill = false;
        // Cabecera
        foreach ($header as $col) {
            $this->Cell(85, 7, $col, 1, 0, 'C', true);
        }

        $this->Ln();

        $this->SetFont('', '');
        $this->SetTextColor(1);
        $this->setX(20);

        // Datos
        foreach ($data as $row)
            //foreach ($row as $col) {
            $this->Cell(85, 7, $row, 1, 0, 'C');
        $this->Ln();
        //}
    }

    function model_Count($header, $data)
    {

        $this->SetTextColor(255);
        $this->SetLineWidth(.3);
        $this->SetFont('', 'B');

        $this->setX(25);
        //$fill = false;
        // Cabecera
        foreach ($header as $col) {
            $this->Cell(40, 7, $col, 1, 0, 'C', true);
        }

        $this->Ln();

        $this->SetFont('', '');
        $this->SetTextColor(1);
        $this->setX(25);

        // Datos
        foreach ($data as $row)
            //foreach ($row as $col) {
            $this->Cell(40, 7, $row, 1, 0, 'C');
        $this->Ln();
        //}
    }
}




$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(200, 10, utf8_decode("Entregas realizadas desde 27-01-2020 a 30-01-2020"), 0, 1, 'C');

$pdf->SetFont('Arial', '', 12);
$header = array('Departamento', 'Cant. de elementos entregados');
$data = array("departamento" => "Administracion", "Cantidad" => 40);

$pdf->SetY(35);
$pdf->department_Count($header, $data);

$pdf->SetY(80);

$header = array('Marca', 'Modelo', 'Tipo', 'Cantidad');
$data = array("Marca" => "HP", "Modelo" => '34A', "Tipo" => 'Toner', 12);

$pdf->model_Count($header, $data);
$pdf->Output();
