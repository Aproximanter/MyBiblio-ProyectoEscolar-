<?php
require_once 'conexion.php';
require_once 'fpdf/fpdf.php';

if (isset($_POST['generar'])) {
    $tabla = $_POST['tabla'];
    $mes = $_POST['mes'];
    $anio = $_POST['anio'];

    // Construir la consulta SQL con los filtros aplicados
    $sql = "SELECT * FROM $tabla";

    // Agregar filtro por mes y año si la tabla tiene una columna de fecha
    switch ($tabla) {
        case 'cartasnoadeudo':
        case 'detalleprestamo':
        case 'extravios':
        case 'prestamos':
        case 'reportessemestrales':
        case 'reservascubiculos':
        case 'servicio':
            $sql .= " WHERE MONTH(fecha_emision) = :mes AND YEAR(fecha_emision) = :anio";
            break;
        default:
            // No agregar filtros de fecha para tablas que no tienen columna de fecha
            break;
    }

    $stmt = $pdo->prepare($sql);
    if (in_array($tabla, ['cartasnoadeudo', 'detalleprestamo', 'extravios', 'prestamos', 'reportessemestrales', 'reservascubiculos', 'servicio'])) {
        $stmt->bindParam(':mes', $mes, PDO::PARAM_INT);
        $stmt->bindParam(':anio', $anio, PDO::PARAM_INT);
    }
    $stmt->execute();
    $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    class PDF extends FPDF {
        function Header() {
            // Título
            $this->SetFont('Arial', 'B', 15);
            $this->Cell(0, 10, 'Reporte de ' . ucfirst($_POST['tabla']), 0, 1, 'C');
            $this->Ln(10);
        }

        function Footer() {
            // Posición a 1.5 cm del final
            $this->SetY(-15);
            // Arial italic 8
            $this->SetFont('Arial', 'I', 8);
            // Número de página
            $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
        }

        function FancyTable($header, $data) {
            // Colores, ancho de línea y fuente en negrita
            $this->SetFillColor(255, 0, 0);
            $this->SetTextColor(255);
            $this->SetDrawColor(128, 0, 0);
            $this->SetLineWidth(.3);
            $this->SetFont('', 'B');
            // Cabecera
            $w = array_fill(0, count($header), 40); // Ancho de las columnas
            foreach ($header as $col) {
                $this->Cell($w[array_search($col, $header)], 7, $col, 1, 0, 'C', true);
            }
            $this->Ln();
            // Restauración de colores y fuentes
            $this->SetFillColor(224, 235, 255);
            $this->SetTextColor(0);
            $this->SetFont('');
            // Datos
            $fill = false;
            foreach ($data as $row) {
                foreach ($row as $col) {
                    $this->Cell($w[array_search($col, $row)], 6, $col, 'LR', 0, 'L', $fill);
                }
                $this->Ln();
                $fill = !$fill;
            }
            $this->Cell(array_sum($w), 0, '', 'T');
        }
    }

    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    if (!empty($datos)) {
        $header = array_keys($datos[0]);
        $pdf->FancyTable($header, $datos);
    } else {
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'No hay datos para mostrar.', 0, 1, 'C');
    }
    $pdf->Output();
}
?>