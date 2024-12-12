<?php
require_once 'fpdf/fpdf.php';
require_once 'conexion.php';
require_once 'prestamosController.php';
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
        $this->Cell(30, 10, 'Informacion del Prestamo', 0, 0, 'C');
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
    $id_prestamo = $_GET['id'];
    $prestamo = obtenerPrestamoPorId($pdo, $id_prestamo);

    if ($prestamo) {
        $usuario = obtenerUsuarioPorId($pdo, $prestamo['id_usuario']);
        $libro = obtenerLibroPorId($pdo, $prestamo['id_libro']);

        $pdf = new PDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);

        // Información del Préstamo
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Detalles del Préstamo', 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(50, 10, 'ID:', 1);
        $pdf->Cell(0, 10, $prestamo['id_prestamo'], 1, 1);
        $pdf->Cell(50, 10, 'Usuario:', 1);
        $pdf->Cell(0, 10, $usuario['nombre'] . ' ' . $usuario['apellido'], 1, 1);
        $pdf->Cell(50, 10, 'Libro:', 1);
        $pdf->Cell(0, 10, $libro['titulo'], 1, 1);
        $pdf->Cell(50, 10, 'Fecha de Préstamo:', 1);
        $pdf->Cell(0, 10, $prestamo['fecha_prestamo'], 1, 1);
        $pdf->Cell(50, 10, 'Fecha de Devolución:', 1);
        $pdf->Cell(0, 10, $prestamo['fecha_devolucion'], 1, 1);
        $pdf->Cell(50, 10, 'Detalle del Préstamo:', 1);
        $pdf->Cell(0, 10, $prestamo['detalle_prestamo'], 1, 1);
        $pdf->Cell(50, 10, 'Estatus:', 1);
        $pdf->Cell(0, 10, ($prestamo['estatus'] == 1) ? 'Activo' : 'Inactivo', 1, 1);

        // Generar el código QR
        $prestamoInfo = json_encode($prestamo);
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($prestamoInfo) . '&size=100x100';

        // Insertar el código QR en el PDF
        $pdf->Ln(10);
        $pdf->Cell(0, 10, 'Codigo QR:', 0, 1, 'C');
        $pdf->Image($qrCodeUrl, ($pdf->GetPageWidth() - 50) / 2, $pdf->GetY(), 50, 50, 'PNG');

        // Salida del PDF
        $pdf->Output('I', 'Prestamo_' . $prestamo['id_prestamo'] . '.pdf');
    } else {
        echo "Préstamo no encontrado.";
    }
} else {
    echo "ID de préstamo no especificado.";
}
?>