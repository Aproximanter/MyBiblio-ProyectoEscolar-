<?php
require_once 'conexion.php';

// Filtros iniciales de mes y año
$mes = isset($_GET['mes']) ? $_GET['mes'] : date('m');
$anio = isset($_GET['anio']) ? $_GET['anio'] : date('Y');

// Nuevos filtros
$nacionalidad = isset($_GET['nacionalidad']) ? $_GET['nacionalidad'] : '';
$estatus = isset($_GET['estatus']) ? $_GET['estatus'] : '';
$nombre = isset($_GET['nombre']) ? $_GET['nombre'] : '';

// Construcción dinámica de la consulta SQL
$sql = "SELECT * FROM autores WHERE MONTH(fecha_creacion) = :mes AND YEAR(fecha_creacion) = :anio";
$params = [
    ':mes' => $mes,
    ':anio' => $anio
];

// Agregar filtros adicionales si se seleccionan
if (!empty($nacionalidad)) {
    $sql .= " AND nacionalidad = :nacionalidad";
    $params[':nacionalidad'] = $nacionalidad;
}
if (!empty($estatus)) {
    $sql .= " AND estatus = :estatus";
    $params[':estatus'] = $estatus;
}
if (!empty($nombre)) {
    $sql .= " AND nombre LIKE :nombre";
    $params[':nombre'] = '%' . $nombre . '%';
}

// Preparar y ejecutar la consulta
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$autores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Mensual de Autores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
        }
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            width: 250px;
            background-color: #343a40;
            color: white;
            padding: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            margin: 10px 0;
        }
        .sidebar a:hover {
            background-color: #495057;
            padding-left: 10px;
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="content">
    <div class="container mt-4">
        <h1 class="text-center mb-4">Reporte Mensual de Autores</h1>
        <form class="row mb-4" method="GET">
            <!-- Filtro de Mes -->
            <div class="col-md-2">
                <label for="mes" class="form-label">Mes</label>
                <select id="mes" name="mes" class="form-select">
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?= $i ?>" <?= $i == $mes ? 'selected' : '' ?>>
                            <?= date('F', mktime(0, 0, 0, $i, 1)) ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <!-- Filtro de Año -->
            <div class="col-md-2">
                <label for="anio" class="form-label">Año</label>
                <input type="number" id="anio" name="anio" class="form-control" value="<?= $anio ?>" min="2000" max="<?= date('Y') ?>">
            </div>
            <!-- Filtro de Nombre -->
            <div class="col-md-2">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="<?= htmlspecialchars($nombre) ?>">
            </div>
            <!-- Filtro de Nacionalidad -->
            <div class="col-md-3">
                <label for="nacionalidad" class="form-label">Nacionalidad</label>
                <input type="text" id="nacionalidad" name="nacionalidad" class="form-control" value="<?= htmlspecialchars($nacionalidad) ?>">
            </div>
            <!-- Filtro de Estatus -->
            <div class="col-md-3">
                <label for="estatus" class="form-label">Estatus</label>
                <select id="estatus" name="estatus" class="form-select">
                    <option value="">Todos</option>
                    <option value="Activo" <?= $estatus == 'Activo' ? 'selected' : '' ?>>Activo</option>
                    <option value="Inactivo" <?= $estatus == 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
                </select>
            </div>
            <!-- Botón para filtrar -->
            <div class="col-md-12 d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-primary">Generar Reporte</button>
            </div>
        </form>

        <!-- Botón para generar el reporte en PDF -->
        <div class="d-flex justify-content-end mb-4">
            <a href="reportePDFAutores.php?mes=<?= $mes ?>&anio=<?= $anio ?>&nacionalidad=<?= urlencode($nacionalidad) ?>&estatus=<?= urlencode($estatus) ?>&nombre=<?= urlencode($nombre) ?>" class="btn btn-success">Generar PDF</a>
        </div>

        <!-- Tabla de resultados -->
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Nacionalidad</th>
                    <th>Estatus</th>
                    <th>Fecha Creación</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($autores)): ?>
                    <?php foreach ($autores as $autor): ?>
                        <tr>
                            <td><?= htmlspecialchars($autor['id_autor']) ?></td>
                            <td><?= htmlspecialchars($autor['nombre']) ?></td>
                            <td><?= htmlspecialchars($autor['apellido']) ?></td>
                            <td><?= htmlspecialchars($autor['nacionalidad']) ?></td>
                            <td><?= htmlspecialchars($autor['estatus']) ?></td>
                            <td><?= htmlspecialchars($autor['fecha_creacion']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No se encontraron resultados</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>