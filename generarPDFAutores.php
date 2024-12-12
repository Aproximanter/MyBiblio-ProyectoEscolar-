<?php
require_once 'fpdf/fpdf.php';
require_once 'conexion.php';
require_once 'autoresController.php';

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
        $this->Cell(30, 10, 'Informacion del Autor', 0, 0, 'C');
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

    // Tabla simple
    function BasicTable($header, $data) {
        // Cabecera
        foreach ($header as $col) {
            $this->Cell(40, 7, $col, 1);
        }
        $this->Ln();
        // Datos
        foreach ($data as $row) {
            foreach ($row as $col) {
                $this->Cell(40, 6, $col, 1);
            }
            $this->Ln();
        }
    }
}

if (isset($_GET['id'])) {
    $id_autor = $_GET['id'];
    $autor = obtenerAutorPorId($pdo, $id_autor);

    if ($autor) {
        $pdf = new PDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);

        // Información del Autor
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Detalles del Autor', 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(50, 10, 'ID:', 1);
        $pdf->Cell(0, 10, $autor['id_autor'], 1, 1);
        $pdf->Cell(50, 10, 'Nombre:', 1);
        $pdf->Cell(0, 10, $autor['nombre'], 1, 1);
        $pdf->Cell(50, 10, 'Apellido:', 1);
        $pdf->Cell(0, 10, $autor['apellido'], 1, 1);
        $pdf->Cell(50, 10, 'Fecha de Nacimiento:', 1);
        $pdf->Cell(0, 10, $autor['fecha_nacimiento'], 1, 1);
        $pdf->Cell(50, 10, 'Nacionalidad:', 1);
        $pdf->Cell(0, 10, $autor['nacionalidad'], 1, 1);

        // Generar el código QR
        $autorInfo = json_encode($autor);
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($autorInfo) . '&size=100x100';

        // Insertar el código QR en el PDF
        $pdf->Ln(10);
        $pdf->Cell(0, 10, 'Codigo QR:', 0, 1, 'C');
        $pdf->Image($qrCodeUrl, ($pdf->GetPageWidth() - 50) / 2, $pdf->GetY(), 50, 50, 'PNG');

        // Salida del PDF
        $pdf->Output('I', 'Autor_' . $autor['id_autor'] . '.pdf');
    } else {
        echo "Autor no encontrado.";
    }
} else {
    echo "ID de autor no especificado.";
}
?>