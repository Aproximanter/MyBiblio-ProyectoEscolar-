<?php
require_once 'fpdf/fpdf.php';
require_once 'conexion.php';
require_once 'editorialesController.php';

class PDF extends FPDF {
    // Encabezado
    function Header() {
        // Logo
        $this->Image('logo.png', 10, 6, 30); // Asegúrate de tener un archivo de logo en tu proyecto
        // Arial bold 15
        $this->SetFont('Arial', 'B', 15);
        // Movernos a la derecha
        $this->Cell(80);
        // Título
        $this->Cell(30, 10, 'Informacion de la Editorial', 0, 0, 'C');
        // Salto de línea
        $this->Ln(20);
    }

    // Pie de página
    function Footer() {
        // Posición a 1.5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Número de página
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

if (isset($_GET['id'])) {
    $id_editorial = $_GET['id'];
    $editorial = obtenerEditorialPorId($pdo, $id_editorial);

    if ($editorial) {
        $pdf = new PDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);

        // Información de la Editorial
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Detalles de la Editorial', 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(50, 10, 'ID:', 1);
        $pdf->Cell(0, 10, $editorial['id_editorial'], 1, 1);
        $pdf->Cell(50, 10, 'Nombre:', 1);
        $pdf->Cell(0, 10, $editorial['nombre'], 1, 1);
        $pdf->Cell(50, 10, 'Dirección:', 1);
        $pdf->Cell(0, 10, $editorial['direccion'], 1, 1);
        $pdf->Cell(50, 10, 'Teléfono:', 1);
        $pdf->Cell(0, 10, $editorial['telefono'], 1, 1);
        $pdf->Cell(50, 10, 'Correo Electrónico:', 1);
        $pdf->Cell(0, 10, $editorial['correo'], 1, 1);

        // Generar el código QR
        $editorialInfo = json_encode($editorial);
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($editorialInfo) . '&size=100x100';

        // Insertar el código QR en el PDF
        $pdf->Ln(10);
        $pdf->Cell(0, 10, 'Codigo QR:', 0, 1, 'C');
        $pdf->Image($qrCodeUrl, ($pdf->GetPageWidth() - 50) / 2, $pdf->GetY(), 50, 50, 'PNG');

        // Salida del PDF
        $pdf->Output('I', 'Editorial_' . $editorial['id_editorial'] . '.pdf');
    } else {
        echo "Editorial no encontrada.";
    }
} else {
    echo "ID de editorial no especificado.";
}
?>