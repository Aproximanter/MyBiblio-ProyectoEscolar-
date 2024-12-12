<?php
require_once 'fpdf/fpdf.php';
require_once 'conexion.php';
require_once 'extraviosController.php';
require_once 'usuariosController.php';
require_once 'librosController.php';

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
        $this->Cell(30, 10, 'Informacion del Extravío', 0, 0, 'C');
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
    $id_extravio = $_GET['id'];
    $extravio = obtenerExtravioPorId($pdo, $id_extravio);

    if ($extravio) {
        $usuario = obtenerUsuarioPorId($pdo, $extravio['id_usuario']);
        $libro = obtenerLibroPorId($pdo, $extravio['id_libro']);

        $pdf = new PDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);

        // Información del Extravío
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Detalles del Extravío', 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(50, 10, 'ID:', 1);
        $pdf->Cell(0, 10, $extravio['id_extravio'], 1, 1);
        $pdf->Cell(50, 10, 'Usuario:', 1);
        $pdf->Cell(0, 10, $usuario['nombre'] . ' ' . $usuario['apellido'], 1, 1);
        $pdf->Cell(50, 10, 'Libro:', 1);
        $pdf->Cell(0, 10, $libro['titulo'], 1, 1);
        $pdf->Cell(50, 10, 'Fecha de Extravío:', 1);
        $pdf->Cell(0, 10, $extravio['fecha_extravio'], 1, 1);
        $pdf->Cell(50, 10, 'Estatus:', 1);
        $pdf->Cell(0, 10, ($extravio['estatus'] == 1) ? 'Activo' : 'Inactivo', 1, 1);

        // Generar el código QR
        $extravioInfo = json_encode($extravio);
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($extravioInfo) . '&size=100x100';

        // Insertar el código QR en el PDF
        $pdf->Ln(10);
        $pdf->Cell(0, 10, 'Codigo QR:', 0, 1, 'C');
        $pdf->Image($qrCodeUrl, ($pdf->GetPageWidth() - 50) / 2, $pdf->GetY(), 50, 50, 'PNG');

        // Salida del PDF
        $pdf->Output('I', 'Extravio_' . $extravio['id_extravio'] . '.pdf');
    } else {
        echo "Extravío no encontrado.";
    }
} else {
    echo "ID de extravío no especificado.";
}
?>