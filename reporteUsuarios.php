<?php
require_once 'conexion.php';

// Filtros de búsqueda
$mes = isset($_GET['mes']) ? $_GET['mes'] : date('m');
$anio = isset($_GET['anio']) ? $_GET['anio'] : date('Y');
$nombre = isset($_GET['nombre']) ? $_GET['nombre'] : '';
$apellido = isset($_GET['apellido']) ? $_GET['apellido'] : '';
$correo = isset($_GET['correo']) ? $_GET['correo'] : '';
$estatus = isset($_GET['estatus']) ? $_GET['estatus'] : '';
$numero_control = isset($_GET['numero_control']) ? $_GET['numero_control'] : '';

// Consulta filtrada
$sql = "SELECT * FROM usuarios WHERE MONTH(fecha_creacion) = :mes AND YEAR(fecha_creacion) = :anio";
if ($nombre) {
    $sql .= " AND nombre LIKE :nombre";
}
if ($apellido) {
    $sql .= " AND apellido LIKE :apellido";
}
if ($correo) {
    $sql .= " AND correo LIKE :correo";
}
if ($estatus !== '') {
    $sql .= " AND estatus = :estatus";
}
if ($numero_control) {
    $sql .= " AND no_de_control LIKE :numero_control";
}

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':mes', $mes, PDO::PARAM_INT);
$stmt->bindParam(':anio', $anio, PDO::PARAM_INT);
if ($nombre) {
    $stmt->bindValue(':nombre', "%$nombre%", PDO::PARAM_STR);
}
if ($apellido) {
    $stmt->bindValue(':apellido', "%$apellido%", PDO::PARAM_STR);
}
if ($correo) {
    $stmt->bindValue(':correo', "%$correo%", PDO::PARAM_STR);
}
if ($estatus !== '') {
    $stmt->bindParam(':estatus', $estatus, PDO::PARAM_INT);
}
if ($numero_control) {
    $stmt->bindValue(':numero_control', "%$numero_control%", PDO::PARAM_STR);
}

$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<style>
 .content {
            margin-left: 300px;
            padding: 20px;
        }

</style>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Mensual de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php include 'sidebar.php'; ?>
<!-- Contenido principal -->
<div class="content">
    <div class="container mt-4">
        <h1 class="text-center mb-4">Reporte Mensual de Usuarios</h1>
        <form class="row mb-4" method="GET">
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
            <div class="col-md-2">
                <label for="anio" class="form-label">Año</label>
                <input type="number" id="anio" name="anio" class="form-control" value="<?= htmlspecialchars($anio) ?>" min="2000" max="<?= date('Y') ?>">
            </div>
            <div class="col-md-2">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="<?= htmlspecialchars($nombre) ?>">
            </div>
            <div class="col-md-2">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text" id="apellido" name="apellido" class="form-control" value="<?= htmlspecialchars($apellido) ?>">
            </div>
            <div class="col-md-2">
                <label for="correo" class="form-label">Correo</label>
                <input type="text" id="correo" name="correo" class="form-control" value="<?= htmlspecialchars($correo) ?>">
            </div>
            <div class="col-md-2">
                <label for="estatus" class="form-label">Estatus</label>
                <select id="estatus" name="estatus" class="form-select">
                    <option value="" <?= $estatus === '' ? 'selected' : '' ?>>Todos</option>
                    <option value="1" <?= $estatus === '1' ? 'selected' : '' ?>>Activo</option>
                    <option value="0" <?= $estatus === '0' ? 'selected' : '' ?>>Inactivo</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="numero_control" class="form-label">Número de Control</label>
                <input type="text" id="numero_control" name="numero_control" class="form-control" value="<?= htmlspecialchars($numero_control) ?>">
            </div>
            <div class="col-md-12 d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-primary">Generar Reporte</button>
            </div>
        </form>

        <div class="d-flex justify-content-end mb-4">
        <a href="generarReporteMensualUsuarios.php?mes=<?= $mes ?>&anio=<?= $anio ?>&nombre=<?= urlencode($nombre) ?>&apellido=<?= urlencode($apellido) ?>&correo=<?= urlencode($correo) ?>&estatus=<?= $estatus ?>&numero_control=<?= urlencode($numero_control) ?>" class="btn btn-success" target="_blank">
    <i class="fas fa-file-pdf"></i> Generar PDF
</a>

        </div>

        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>No. de Control</th>
                    <th>Estatus</th>
                    <th>Fecha Creación</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($usuarios) > 0): ?>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?= htmlspecialchars($usuario['id_usuario']) ?></td>
                            <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                            <td><?= htmlspecialchars($usuario['apellido']) ?></td>
                            <td><?= htmlspecialchars($usuario['correo']) ?></td>
                            <td><?= htmlspecialchars($usuario['telefono']) ?></td>
                            <td><?= htmlspecialchars($usuario['direccion']) ?></td>
                            <td><?= htmlspecialchars($usuario['no_de_control']) ?></td>
                            <td><?= $usuario['estatus'] ? 'Activo' : 'Inactivo' ?></td>
                            <td><?= htmlspecialchars($usuario['fecha_creacion']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center">No se encontraron usuarios.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
