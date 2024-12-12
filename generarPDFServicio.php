<?php
require_once 'fpdf/fpdf.php';
require_once 'conexion.php';
require_once 'serviciosController.php';
require_once 'usuariosController.php';

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
        $this->Cell(30, 10, 'Informacion del Servicio', 0, 0, 'C');
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
    $id_servicio = $_GET['id'];
    $servicio = obtenerServicioPorId($pdo, $id_servicio);

    if ($servicio) {
        $usuario = obtenerUsuarioPorId($pdo, $servicio['id_usuario']);

        $pdf = new PDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);

        // Información del Servicio
        $pdf->SetFont('Arial', 'B', 14);
             $pdf->Cell(0, 10, 'Detalles del Servicio', 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(50, 10, 'ID:', 1);
        $pdf->Cell(0, 10, $servicio['id_servicio'], 1, 1);
        $pdf->Cell(50, 10, 'Usuario:', 1);
        $pdf->Cell(0, 10, $usuario['nombre'] . ' ' . $usuario['apellido'], 1, 1);
        $pdf->Cell(50, 10, 'Fecha de Servicio:', 1);
        $pdf->Cell(0, 10, $servicio['fecha_servicio'], 1, 1);
        $pdf->Cell(50, 10, 'Hora de Inicio:', 1);
        $pdf->Cell(0, 10, $servicio['hora_inicio'], 1, 1);
        $pdf->Cell(50, 10, 'Hora de Fin:', 1);
        $pdf->Cell(0, 10, $servicio['hora_fin'], 1, 1);
        $pdf->Cell(50, 10, 'Estatus:', 1);
        $pdf->Cell(0, 10, ($servicio['estatus'] == 1) ? 'Activo' : 'Inactivo', 1, 1);

        // Generar el código QR
        $servicioInfo = json_encode($servicio);
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($servicioInfo) . '&size=100x100';

        // Insertar el código QR en el PDF
        $pdf->Ln(10);
        $pdf->Cell(0, 10, 'Codigo QR:', 0, 1, 'C');
        $pdf->Image($qrCodeUrl, ($pdf->GetPageWidth() - 50) / 2, $pdf->GetY(), 50, 50, 'PNG');

        // Salida del PDF
        $pdf->Output('I', 'Servicio_' . $servicio['id_servicio'] . '.pdf');
    } else {
        echo "Servicio no encontrado.";
    }
} else {
    echo "ID de servicio no especificado.";
}
?>