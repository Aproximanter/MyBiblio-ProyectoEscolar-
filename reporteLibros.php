<?php
require_once 'conexion.php';

// Filtros iniciales de mes y año
$mes = isset($_GET['mes']) ? $_GET['mes'] : date('m');
$anio = isset($_GET['anio']) ? $_GET['anio'] : date('Y');

// Nuevos filtros
$titulo = isset($_GET['titulo']) ? $_GET['titulo'] : '';
$materia = isset($_GET['materia']) ? $_GET['materia'] : '';
$codigo_barras = isset($_GET['codigo_barras']) ? $_GET['codigo_barras'] : '';
$estatus = isset($_GET['estatus']) ? $_GET['estatus'] : '';

// Construcción dinámica de la consulta SQL
$sql = "SELECT * FROM libros WHERE MONTH(fecha_creacion) = :mes AND YEAR(fecha_creacion) = :anio";
$params = [
    ':mes' => $mes,
    ':anio' => $anio
];

// Agregar filtros adicionales si se seleccionan
if (!empty($titulo)) {
    $sql .= " AND titulo LIKE :titulo";
    $params[':titulo'] = '%' . $titulo . '%';
}
if (!empty($materia)) {
    $sql .= " AND materia LIKE :materia";
    $params[':materia'] = '%' . $materia . '%';
}
if (!empty($codigo_barras)) {
    $sql .= " AND codigo_barras = :codigo_barras";
    $params[':codigo_barras'] = $codigo_barras;
}
if (!empty($estatus)) {
    $sql .= " AND estatus = :estatus";
    $params[':estatus'] = $estatus;
}

// Preparar y ejecutar la consulta
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$libros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Mensual de Libros</title>
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
        <h1 class="text-center mb-4">Reporte Mensual de Libros</h1>
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
            <!-- Filtro de Título -->
            <div class="col-md-2">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" id="titulo" name="titulo" class="form-control" value="<?= htmlspecialchars($titulo) ?>">
            </div>
            <!-- Filtro de Materia -->
            <div class="col-md-2">
                <label for="materia" class="form-label">Materia</label>
                <input type="text" id="materia" name="materia" class="form-control" value="<?= htmlspecialchars($materia) ?>">
            </div>
            <!-- Filtro de Código de Barras -->
            <div class="col-md-2">
                <label for="codigo_barras" class="form-label">Código de Barras</label>
                <input type="text" id="codigo_barras" name="codigo_barras" class="form-control" value="<?= htmlspecialchars($codigo_barras) ?>">
            </div>
            <!-- Filtro de Estatus -->
            <div class="col-md-2">
                <label for="estatus" class="form-label">Estatus</label>
                <select id="estatus" name="estatus" class="form-select">
                    <option value="">Todos</option>
                    <option value="1" <?= $estatus == '1' ? 'selected' : '' ?>>Activo</option>
                    <option value="0" <?= $estatus == '0' ? 'selected' : '' ?>>Inactivo</option>
                </select>
            </div>
            <!-- Botón para filtrar -->
            <div class="col-md-12 d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-primary">Generar Reporte</button>
            </div>
        </form>

        <!-- Botón para generar el reporte en PDF -->
        <div class="d-flex justify-content-end mb-4">
            <a href="reportePDFLibros.php?mes=<?= $mes ?>&anio=<?= $anio ?>&titulo=<?= urlencode($titulo) ?>&materia=<?= urlencode($materia) ?>&codigo_barras=<?= urlencode($codigo_barras) ?>&estatus=<?= urlencode($estatus) ?>" class="btn btn-success">Generar PDF</a>
        </div>

        <!-- Tabla de resultados -->
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Materia</th>
                    <th>Código de Barras</th>
                    <th>Código de Color</th>
                    <th>Signatura</th>
                    <th>Cantidad</th>
                    <th>Estatus</th>
                    <th>Fecha Creación</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($libros)): ?>
                    <?php foreach ($libros as $libro): ?>
                        <tr>
                            <td><?= htmlspecialchars($libro['id_libro']) ?></td>
                            <td><?= htmlspecialchars($libro['titulo']) ?></td>
                            <td><?= htmlspecialchars($libro['materia']) ?></td>
                            <td><?= htmlspecialchars($libro['codigo_barras']) ?></td>
                            <td><?= htmlspecialchars($libro['codigo_color']) ?></td>
                            <td><?= htmlspecialchars($libro['signatura']) ?></td>
                            <td><?= htmlspecialchars($libro['cantidad']) ?></td>
                            <td><?= $libro['estatus'] ? 'Activo' : 'Inactivo' ?></td>
                            <td><?= htmlspecialchars($libro['fecha_creacion']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center">No se encontraron resultados</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>