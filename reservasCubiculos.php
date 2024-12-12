<?php
require_once 'conexion.php';
require_once 'reservasCubiculosController.php';
require_once 'usuariosController.php'; // Asegúrate de tener un controlador para los usuarios
require_once 'cubiculosController.php'; // Asegúrate de tener un controlador para los cubículos

$reservas = obtenerReservasCubiculos($pdo);
$usuarios = obtenerUsuarios($pdo);
$cubiculos = obtenerCubiculos($pdo); // Asegúrate de tener una función para obtener los cubículos

// Manejo de notificaciones
$notificacion = '';
$tipo_notificacion = '';
if (isset($_GET['success'])) {
    $tipo_notificacion = 'success';
    if ($_GET['success'] == 'created') {
        $notificacion = "Reserva de cubículo agregada exitosamente.";
    } elseif ($_GET['success'] == 'updated') {
        $notificacion = "Reserva de cubículo actualizada exitosamente.";
    } elseif ($_GET['success'] == 'deleted') {
        $notificacion = "Reserva de cubículo eliminada exitosamente.";
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
    <title>Manejo de Reservas de Cubículos</title>
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
        <h1 class="text-center mb-4">Manejo de Reservas de Cubículos</h1>

        <!-- Mostrar notificaciones -->
        <?php if (!empty($notificacion)): ?>
            <div class="notif-message">
                <div class="fancy-notification <?php echo $tipo_notificacion; ?>">
                    <?php echo $notificacion; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Formulario para crear reserva -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <i class="fas fa-plus"></i> Agregar Nueva Reserva
            </div>
            <div class="card-body">
                <form method="POST" action="reservasCubiculosController.php">
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
                            <label for="id_cubiculo" class="form-label">Cubículo</label>
                            <select name="id_cubiculo" id="id_cubiculo" class="form-control" required>
                                <?php foreach ($cubiculos as $cubiculo): ?>
                                    <option value="<?php echo $cubiculo['id_cubiculo']; ?>">
                                        <?php echo $cubiculo['tipo']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="fecha_reserva" class="form-label">Fecha de Reserva</label>
                            <input type="date" name="fecha_reserva" class="form-control" id="fecha_reserva" required>
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
                    <button type="submit" name="create" class="btn btn-success"><i class="fas fa-plus"></i> Agregar Reserva</button>
                </form>
            </div>
        </div>

        <!-- Tabla de reservas -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-list"></i> Lista de Reservas
            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Cubículo</th>
                            <th>Fecha de Reserva</th>
                            <th>Hora de Inicio</th>
                            <th>Hora de Fin</th>
                            <th>Estatus</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($reservas): ?>
                            <?php foreach ($reservas as $reserva): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($reserva['id_reserva']); ?></td>
                                    <td><?php echo htmlspecialchars($reserva['usuario_nombre'] . ' ' . $reserva['usuario_apellido']); ?></td>
                                    <td><?php echo htmlspecialchars($reserva['cubiculo_tipo']); ?></td>
                                    <td><?php echo htmlspecialchars($reserva['fecha_reserva']); ?></td>
                                    <td><?php echo htmlspecialchars($reserva['hora_inicio']); ?></td>
                                    <td><?php echo htmlspecialchars($reserva['hora_fin']); ?></td>
                                    <td>
                                        <?php echo ($reserva['estatus'] == 1) ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-secondary">Inactivo</span>'; ?>
                                    </td>
                                    <td>
                                        <!-- Botones de acciones -->
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $reserva['id_reserva']; ?>">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        <form method="POST" action="reservasCubiculosController.php" style="display:inline-block;">
                                            <input type="hidden" name="id_reserva" value="<?php echo $reserva['id_reserva']; ?>">
                                            <button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta reserva?');">
                                                <i class="fas fa-trash-alt"></i> Eliminar
                                            </button>
                                        </form>
                                        <a href="generarPDFReservaCubiculo.php?id=<?php echo $reserva['id_reserva']; ?>" target="_blank" class="btn btn-info btn-sm">
                                            <i class="fas fa-file-pdf"></i> PDF
                                        </a>
                                    </td>
                                </tr>

                                <!-- Modal para editar -->
                                <div class="modal fade" id="editModal<?php echo $reserva['id_reserva']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $reserva['id_reserva']; ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel<?php echo $reserva['id_reserva']; ?>">Editar Reserva</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form method="POST" action="reservasCubiculosController.php">
                                                <div class="modal-body">
                                                    <input type="hidden" name="id_reserva" value="<?php echo $reserva['id_reserva']; ?>">
                                                    <div class="row">
                                                        <div class="col-md-4 mb-3">
                                                            <label for="id_usuario<?php echo $reserva['id_reserva']; ?>" class="form-label">Usuario</label>
                                                            <select name="id_usuario" id="id_usuario<?php echo $reserva['id_reserva']; ?>" class="form-control" required>
                                                                <?php foreach ($usuarios as $usuario): ?>
                                                                    <option value="<?php echo $usuario['id_usuario']; ?>" <?php echo ($reserva['id_usuario'] == $usuario['id_usuario']) ? 'selected' : ''; ?>>
                                                                        <?php echo $usuario['nombre'] . ' ' . $usuario['apellido']; ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="id_cubiculo<?php echo $reserva['id_reserva']; ?>" class="form-label">Cubículo</label>
                                                            <select name="id_cubiculo" id="id_cubiculo<?php echo $reserva['id_reserva']; ?>" class="form-control" required>
                                                                <?php foreach ($cubiculos as $cubiculo): ?>
                                                                    <option value="<?php echo $cubiculo['id_cubiculo']; ?>" <?php echo ($reserva['id_cubiculo'] == $cubiculo['id_cubiculo']) ? 'selected' : ''; ?>>
                                                                        <?php echo $cubiculo['tipo']; ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="fecha_reserva<?php echo $reserva['id_reserva']; ?>" class="form-label">Fecha de Reserva</label>
                                                            <input type="date" name="fecha_reserva" class="form-control" id="fecha_reserva<?php echo $reserva['id_reserva']; ?>" value="<?php echo htmlspecialchars($reserva['fecha_reserva']); ?>" required>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="hora_inicio<?php echo $reserva['id_reserva']; ?>" class="form-label">Hora de Inicio</label>
                                                            <input type="time" name="hora_inicio" class="form-control" id="hora_inicio<?php echo $reserva['id_reserva']; ?>" value="<?php echo htmlspecialchars($reserva['hora_inicio']); ?>" required>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="hora_fin<?php echo $reserva['id_reserva']; ?>" class="form-label">Hora de Fin</label>
                                                            <input type="time" name="hora_fin" class="form-control" id="hora_fin<?php echo $reserva['id_reserva']; ?>" value="<?php echo htmlspecialchars($reserva['hora_fin']); ?>" required>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="estatus<?php echo $reserva['id_reserva']; ?>" class="form-label">Estatus</label>
                                                            <select name="estatus" id="estatus<?php echo $reserva['id_reserva']; ?>" class="form-control" required>
                                                                <option value="1" <?php echo ($reserva['estatus'] == 1) ? 'selected' : ''; ?>>Activo</option>
                                                                <option value="0" <?php echo ($reserva['estatus'] == 0) ? 'selected' : ''; ?>>Inactivo</option>
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
                                <td colspan="8" class="text-center">No hay reservas registradas.</td>
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

