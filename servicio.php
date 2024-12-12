<?php
require_once 'conexion.php';
require_once 'serviciosController.php';
require_once 'usuariosController.php';

$servicios = obtenerServicios($pdo);
$usuarios = obtenerUsuarios($pdo);

// Manejo de notificaciones
$notificacion = '';
$tipo_notificacion = '';
if (isset($_GET['success'])) {
    $tipo_notificacion = 'success';
    if ($_GET['success'] == 'created') {
        $notificacion = "Servicio agregado exitosamente.";
    } elseif ($_GET['success'] == 'updated') {
        $notificacion = "Servicio actualizado exitosamente.";
    } elseif ($_GET['success'] == 'deleted') {
        $notificacion = "Servicio eliminado exitosamente.";
    }
} elseif (isset($_GET['error'])) {
    $tipo_notificacion = 'error';
    $notificacion = "Hubo un error en la operación.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manejo de Servicios</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome para iconos -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Fancy Notifications CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancy-notifications/1.0.0/fancy-notifications.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .content {
            margin-left: 300px;
            padding: 20px;
        }
        .notif-message {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
    </style>
</head>
<body>

<!-- Menú lateral -->
<?php include 'sidebar.php'; ?>

<!-- Contenido principal -->
<div class="content">
    <div class="container-fluid">
        <h1 class="text-center mb-4">Manejo de Servicios</h1>

        <!-- Mostrar notificaciones -->
        <?php if (!empty($notificacion)): ?>
            <div class="notif-message">
                <div class="fancy-notification <?php echo $tipo_notificacion; ?>">
                    <?php echo $notificacion; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Formulario para crear servicio -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <i class="fas fa-plus"></i> Agregar Nuevo Servicio
            </div>
            <div class="card-body">
                <form method="POST" action="serviciosController.php">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="id_usuario" class="form-label">Usuario</label>
                            <select name="id_usuario" id="id_usuario" class="form-control" required>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <option value="<?php echo $usuario['id_usuario']; ?>">
                                        <?php echo $usuario['nombre'] . ' ' . $usuario['apellido']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="fecha_servicio" class="form-label">Fecha de Servicio</label>
                            <input type="date" name="fecha_servicio" class="form-control" id="fecha_servicio" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="hora_inicio" class="form-label">Hora de Inicio</label>
                            <input type="time" name="hora_inicio" class="form-control" id="hora_inicio" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="hora_fin" class="form-label">Hora de Fin</label>
                            <input type="time" name="hora_fin" class="form-control" id="hora_fin" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="estatus" class="form-label">Estatus</label>
                            <select name="estatus" id="estatus" class="form-control" required>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" name="create" class="btn btn-success"><i class="fas fa-plus"></i> Agregar Servicio</button>
                </form>
            </div>
        </div>

        <!-- Tabla de servicios -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-list"></i> Lista de Servicios
            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Fecha de Servicio</th>
                            <th>Hora de Inicio</th>
                            <th>Hora de Fin</th>
                            <th>Estatus</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($servicios): ?>
                            <?php foreach ($servicios as $servicio): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($servicio['id_servicio']); ?></td>
                                    <td><?php echo htmlspecialchars($servicio['nombre'] . ' ' . $servicio['apellido']); ?></td>
                                    <td><?php echo htmlspecialchars($servicio['fecha_servicio']); ?></td>
                                    <td><?php echo htmlspecialchars($servicio['hora_inicio']); ?></td>
                                    <td><?php echo htmlspecialchars($servicio['hora_fin']); ?></td>
                                    <td>
                                        <?php echo ($servicio['estatus'] == 1) ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-secondary">Inactivo</span>'; ?>
                                    </td>
                                    <td>
                                        <!-- Botones de acciones -->
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $servicio['id_servicio']; ?>">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        <form method="POST" action="serviciosController.php" style="display:inline-block;">
                                            <input type="hidden" name="id_servicio" value="<?php echo $servicio['id_servicio']; ?>">
                                            <button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este servicio?');">
                                                <i class="fas fa-trash-alt"></i> Eliminar
                                            </button>
                                        </form>
                                        <a href="generarPDFServicio.php?id=<?php echo $servicio['id_servicio']; ?>" target="_blank" class="btn btn-info btn-sm">
                                            <i class="fas fa-file-pdf"></i> PDF
                                        </a>
                                    </td>
                                </tr>

                                <!-- Modal para editar -->
                                <div class="modal fade" id="editModal<?php echo $servicio['id_servicio']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $servicio['id_servicio']; ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel<?php echo $servicio['id_servicio']; ?>">Editar Servicio</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form method="POST" action="serviciosController.php">
                                                <div class="modal-body">
                                                    <input type="hidden" name="id_servicio" value="<?php echo $servicio['id_servicio']; ?>">
                                                    <div class="row">
                                                        <div class="col-md-4 mb-3">
                                                            <label for="id_usuario<?php echo $servicio['id_servicio']; ?>" class="form-label">Usuario</label>
                                                            <select name="id_usuario" id="id_usuario<?php echo $servicio['id_servicio']; ?>" class="form-control" required>
                                                                <?php foreach ($usuarios as $usuario): ?>
                                                                    <option value="<?php echo $usuario['id_usuario']; ?>" <?php echo ($servicio['id_usuario'] == $usuario['id_usuario']) ? 'selected' : ''; ?>>
                                                                        <?php echo $usuario['nombre'] . ' ' . $usuario['apellido']; ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="fecha_servicio<?php echo $servicio['id_servicio']; ?>" class="form-label">Fecha de Servicio</label>
                                                            <input type="date" name="fecha_servicio" class="form-control" id="fecha_servicio<?php echo $servicio['id_servicio']; ?>" value="<?php echo htmlspecialchars($servicio['fecha_servicio']); ?>" required>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="hora_inicio<?php echo $servicio['id_servicio']; ?>" class="form-label">Hora de Inicio</label>
                                                            <input type="time" name="hora_inicio" class="form-control" id="hora_inicio<?php echo $servicio['id_servicio']; ?>" value="<?php echo htmlspecialchars($servicio['hora_inicio']); ?>" required>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="hora_fin<?php echo $servicio['id_servicio']; ?>" class="form-label">Hora de Fin</label>
                                                            <input type="time" name="hora_fin" class="form-control" id="hora_fin<?php echo $servicio['id_servicio']; ?>" value="<?php echo htmlspecialchars($servicio['hora_fin']); ?>" required>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="estatus<?php echo $servicio['id_servicio']; ?>" class="form-label">Estatus</label>
                                                            <select name="estatus" id="estatus<?php echo $servicio['id_servicio']; ?>" class="form-control" required>
                                                                <option value="1" <?php echo ($servicio['estatus'] == 1) ? 'selected' : ''; ?>>Activo</option>
                                                                <option value="0" <?php echo ($servicio['estatus'] == 0) ? 'selected' : ''; ?>>Inactivo</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                    <button type="submit" name="update" class="btn btn-primary">Guardar cambios</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">No hay servicios registrados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS y dependencias -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- Fancy Notifications JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancy-notifications/1.0.0/fancy-notifications.min.js"></script>
</body>
</html>