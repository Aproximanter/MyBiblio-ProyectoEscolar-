<?php
session_start();
require_once 'conexion.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Tablas disponibles
$tablas = [
    'autores' => 'Autores',
    'cartasnoadeudo' => 'Cartas No Adeudo',
    'cubiculos' => 'Cubículos',
    'cuentas' => 'Cuentas',
    'detalleprestamo' => 'Detalle Préstamo',
    'editoriales' => 'Editoriales',
    'extravios' => 'Extravíos',
    'libros' => 'Libros',
    'multas' => 'Multas',
    'prestamos' => 'Préstamos',
    'reportessemestrales' => 'Reportes Semestrales',
    'reservascubiculos' => 'Reservas Cubículos',
    'servicio' => 'Servicio',
    'usuarios' => 'Usuarios'
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generación de Reportes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <!-- Incluir la barra lateral -->
    <?php include 'sidebar.php'; ?>

    <!-- Contenido del Dashboard -->
    <div class="content" style="margin-left: 260px; padding: 20px;">
        <h1>Generación de Reportes</h1>
        <p>Usuario logueado: <strong><?php echo $_SESSION['usuario']; ?></strong></p>
        
        <!-- Formulario para seleccionar filtros -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-filter"></i> Seleccionar Filtros
            </div>
            <div class="card-body">
                <form method="POST" action="generarReporte.php">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="tabla" class="form-label">Tabla</label>
                            <select name="tabla" id="tabla" class="form-control" required>
                                <?php foreach ($tablas as $key => $value): ?>
                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="mes" class="form-label">Mes</label>
                            <select name="mes" id="mes" class="form-control" required>
                                <?php for ($m = 1; $m <= 12; $m++): ?>
                                    <option value="<?php echo $m; ?>"><?php echo date('F', mktime(0, 0, 0, $m, 1)); ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="anio" class="form-label">Año</label>
                            <input type="number" name="anio" class="form-control" id="anio" value="<?php echo date('Y'); ?>" required>
                        </div>
                    </div>
                    <button type="submit" name="generar" class="btn btn-primary"><i class="fas fa-file-alt"></i> Generar Reporte</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>