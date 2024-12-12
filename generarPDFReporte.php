<?php
require_once 'conexion.php';
require_once 'fpdf/fpdf.php';

if (isset($_POST['generar'])) {
    $tabla = $_POST['tabla'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    $filtros = [];
    if ($fecha_inicio) {
        $filtros[] = "fecha_servicio >= '$fecha_inicio'";
    }
    if ($fecha_fin) {
        $filtros[] = "fecha_servicio <= '$fecha_fin'";
    }

    $datos = obtenerDatos($pdo, $tabla, $filtros);

    class PDF extends FPDF {
        function Header() {
            $this->SetFont('Arial', 'B', 15);
            $this->Cell(0, 10, 'Reporte de ' . ucfirst($_POST['tabla']), 0, 1, 'C');
            $this->Ln(10);
        }

        function Footer() {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
        }

        function FancyTable($header, $data) {
            $this->SetFillColor(255, 0, 0);
            $this->SetTextColor(255);
            $this->SetDrawColor(128, 0, 0);
            $this->SetLineWidth(.3);
            $this->SetFont('', 'B');
            $w = array(40, 35, 40, 45);
            for ($i = 0; $i < count($header); $i++) {
                $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
            }
            $this->Ln();
            $this->SetFillColor(224, 235, 255);
            $this->SetTextColor(0);
            $this->SetFont('');
            $fill = false;
            foreach ($data as $row) {
                $this->Cell($w[0], 6, $row[0], 'LR', 0, 'L', $fill);
                $this->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill);
                $this->Cell($w[2], 6, $row[2], 'LR', 0, 'L', $fill);
                $this->Cell($w[3], 6, $row[3], 'LR', 0, 'L', $fill);
                $this->Ln();
                $fill = !$fill;
            }
            $this->Cell(array_sum($w), 0, '', 'T');
        }
    }

    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $header = array_keys($datos[0]);
    $pdf->FancyTable($header, $datos);
    $pdf->Output();
}
?>