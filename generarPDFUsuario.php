<?php
require_once 'fpdf/fpdf.php';
require_once 'conexion.php';
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
        $this->Cell(30, 10, 'Informacion del Usuario', 0, 0, 'C');
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
    $id_usuario = $_GET['id'];
    $usuario = obtenerUsuarioPorId($pdo, $id_usuario);

    if ($usuario) {
        $pdf = new PDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);

        // Información del Usuario
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Detalles del Usuario', 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(50, 10, 'ID:', 1);
        $pdf->Cell(0, 10, $usuario['id_usuario'], 1, 1);
        $pdf->Cell(50, 10, 'Nombre:', 1);
        $pdf->Cell(0, 10, $usuario['nombre'], 1, 1);
        $pdf->Cell(50, 10, 'Apellido:', 1);
        $pdf->Cell(0, 10, $usuario['apellido'], 1, 1);
        $pdf->Cell(50, 10, 'Correo:', 1);
        $pdf->Cell(0, 10, $usuario['correo'], 1, 1);
        $pdf->Cell(50, 10, 'Teléfono:', 1);
        $pdf->Cell(0, 10, $usuario['telefono'], 1, 1);
        $pdf->Cell(50, 10, 'Dirección:', 1);
        $pdf->Cell(0, 10, $usuario['direccion'], 1, 1);
        $pdf->Cell(50, 10, 'No. de Control:', 1);
        $pdf->Cell(0, 10, $usuario['no_de_control'], 1, 1);
        $pdf->Cell(50, 10, 'Estatus:', 1);
        $pdf->Cell(0, 10, ($usuario['estatus'] == 1) ? 'Activo' : 'Inactivo', 1, 1);

        // Generar el código QR
        $usuarioInfo = json_encode($usuario);
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($usuarioInfo) . '&size=100x100';

        // Insertar el código QR en el PDF
        $pdf->Ln(10);
        $pdf->Cell(0, 10, 'Codigo QR:', 0, 1, 'C');
        $pdf->Image($qrCodeUrl, ($pdf->GetPageWidth() - 50) / 2, $pdf->GetY(), 50, 50, 'PNG');

        // Salida del PDF
        $pdf->Output('I', 'Usuario_' . $usuario['id_usuario'] . '.pdf');
    } else {
        echo "Usuario no encontrado.";
    }
} else {
    echo "ID de usuario no especificado.";
}
?>