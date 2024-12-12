<?php
require_once 'conexion.php';
require_once 'prestamosController.php';
require_once 'usuariosController.php';
require_once 'librosController.php'; // Asegúrate de tener un controlador para los libros

$prestamos = obtenerPrestamos($pdo);
$usuarios = obtenerUsuarios($pdo);
$libros = obtenerLibros($pdo); // Asegúrate de tener una función para obtener los libros

// Manejo de notificaciones
$notificacion = '';
$tipo_notificacion = '';
if (isset($_GET['success'])) {
    $tipo_notificacion = 'success';
    if ($_GET['success'] == 'created') {
        $notificacion = "Préstamo agregado exitosamente.";
    } elseif ($_GET['success'] == 'updated') {
        $notificacion = "Préstamo actualizado exitosamente.";
    } elseif ($_GET['success'] == 'deleted') {
        $notificacion = "Préstamo eliminado exitosamente.";
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
    <title>Manejo de Préstamos</title>
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
        <h1 class="text-center mb-4">Manejo de Préstamos</h1>

        <!-- Mostrar notificaciones -->
        <?php if (!empty($notificacion)): ?>
            <div class="notif-message">
                <div class="fancy-notification <?php echo $tipo_notificacion; ?>">
                    <?php echo $notificacion; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Formulario para crear préstamo -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <i class="fas fa-plus"></i> Agregar Nuevo Préstamo
            </div>
            <div class="card-body">
                <form method="POST" action="prestamosController.php">
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
                            <label for="id_libro" class="form-label">Libro</label>
                            <select name="id_libro" id="id_libro" class="form-control" required>
                                <?php foreach ($libros as $libro): ?>
                                    <option value="<?php echo $libro['id_libro']; ?>">
                                        <?php echo $libro['titulo']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="fecha_prestamo" class="form-label">Fecha de Préstamo</label>
                            <input type="date" name="fecha_prestamo" class="form-control" id="fecha_prestamo" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="fecha_devolucion" class="form-label">Fecha de Devolución</label>
                            <input type="date" name="fecha_devolucion" class="form-control" id="fecha_devolucion" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="detalle_prestamo" class="form-label">Detalle del Préstamo</label>
                            <textarea name="detalle_prestamo" class="form-control" id="detalle_prestamo" placeholder="Detalle del préstamo" required></textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="estatus" class="form-label">Estatus</label>
                            <select name="estatus" id="estatus" class="form-control" required>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" name="create" class="btn btn-success"><i class="fas fa-plus"></i> Agregar Préstamo</button>
                </form>
            </div>
        </div>

        <!-- Tabla de préstamos -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-list"></i> Lista de Préstamos
            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Libro</th>
                            <th>Fecha de Préstamo</th>
                            <th>Fecha de Devolución</th>
                            <th>Detalle del Préstamo</th>
                            <th>Estatus</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($prestamos): ?>
                            <?php foreach ($prestamos as $prestamo): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($prestamo['id_prestamo']); ?></td>
                                    <td><?php echo htmlspecialchars($prestamo['usuario_nombre'] . ' ' . $prestamo['usuario_apellido']); ?></td>
                                    <td><?php echo htmlspecialchars($prestamo['libro_titulo']); ?></td>
                                    <td><?php echo htmlspecialchars($prestamo['fecha_prestamo']); ?></td>
                                    <td><?php echo htmlspecialchars($prestamo['fecha_devolucion']); ?></td>
                                    <td><?php echo htmlspecialchars($prestamo['detalle_prestamo']); ?></td>
                                    <td>
                                        <?php echo ($prestamo['estatus'] == 1) ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-secondary">Inactivo</span>'; ?>
                                    </td>
                                    <td>
                                        <!-- Botones de acciones -->
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $prestamo['id_prestamo']; ?>">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        <form method="POST" action="prestamosController.php" style="display:inline-block;">
                                            <input type="hidden" name="id_prestamo" value="<?php echo $prestamo['id_prestamo']; ?>">
                                            <button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este préstamo?');">
                                                <i class="fas fa-trash-alt"></i> Eliminar
                                            </button>
                                        </form>
                                        <a href="generarPDFPrestamo.php?id=<?php echo $prestamo['id_prestamo']; ?>" target="_blank" class="btn btn-info btn-sm">
                                            <i class="fas fa-file-pdf"></i> PDF
                                        </a>
                                    </td>
                                </tr>

                                <!-- Modal para editar -->
                                <div class="modal fade" id="editModal<?php echo $prestamo['id_prestamo']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $prestamo['id_prestamo']; ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel<?php echo $prestamo['id_prestamo']; ?>">Editar Préstamo</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form method="POST" action="prestamosController.php">
                                                <div class="modal-body">
                                                    <input type="hidden" name="id_prestamo" value="<?php echo $prestamo['id_prestamo']; ?>">
                                                    <div class="row">
                                                        <div class="col-md-4 mb-3">
                                                            <label for="id_usuario<?php echo $prestamo['id_prestamo']; ?>" class="form-label">Usuario</label>
                                                            <select name="id_usuario" id="id_usuario<?php echo $prestamo['id_prestamo']; ?>" class="form-control" required>
                                                                <?php foreach ($usuarios as $usuario): ?>
                                                                    <option value="<?php echo $usuario['id_usuario']; ?>" <?php echo ($prestamo['id_usuario'] == $usuario['id_usuario']) ? 'selected' : ''; ?>>
                                                                        <?php echo $usuario['nombre'] . ' ' . $usuario['apellido']; ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="id_libro<?php echo $prestamo['id_prestamo']; ?>" class="form-label">Libro</label>
                                                            <select name="id_libro" id="id_libro<?php echo $prestamo['id_prestamo']; ?>" class="form-control" required>
                                                                <?php foreach ($libros as $libro): ?>
                                                                    <option value="<?php echo $libro['id_libro']; ?>" <?php echo ($prestamo['id_libro'] == $libro['id_libro']) ? 'selected' : ''; ?>>
                                                                        <?php echo $libro['titulo']; ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="fecha_prestamo<?php echo $prestamo['id_prestamo']; ?>" class="form-label">Fecha de Préstamo</label>
                                                            <input type="date" name="fecha_prestamo" class="form-control" id="fecha_prestamo<?php echo $prestamo['id_prestamo']; ?>" value="<?php echo htmlspecialchars($prestamo['fecha_prestamo']); ?>" required>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="fecha_devolucion<?php echo $prestamo['id_prestamo']; ?>" class="form-label">Fecha de Devolución</label>
                                                            <input type="date" name="fecha_devolucion" class="form-control" id="fecha_devolucion<?php echo $prestamo['id_prestamo']; ?>" value="<?php echo htmlspecialchars($prestamo['fecha_devolucion']); ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8 mb-3">
                                                            <label for="detalle_prestamo<?php echo $prestamo['id_prestamo']; ?>" class="form-label">Detalle del Préstamo</label>
                                                            <textarea name="detalle_prestamo" class="form-control" id="detalle_prestamo<?php echo $prestamo['id_prestamo']; ?>" required><?php echo htmlspecialchars($prestamo['detalle_prestamo']); ?></textarea>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="estatus<?php echo $prestamo['id_prestamo']; ?>" class="form-label">Estatus</label>
                                                            <select name="estatus" id="estatus<?php echo $prestamo['id_prestamo']; ?>" class="form-control" required>
                                                                <option value="1" <?php echo ($prestamo['estatus'] == 1) ? 'selected' : ''; ?>>Activo</option>
                                                                <option value="0" <?php echo ($prestamo['estatus'] == 0) ? 'selected' : ''; ?>>Inactivo</option>
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
                                <td colspan="8" class="text-center">No hay préstamos registrados.</td>
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